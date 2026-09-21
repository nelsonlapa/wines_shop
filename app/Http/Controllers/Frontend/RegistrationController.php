<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function my()
    {
        $purchases = Registration::where('user_id', Auth::id())
            ->with(['event', 'seat', 'order'])
            ->latest()
            ->get()
            ->groupBy(fn (Registration $registration) => $registration->order_id ?: 'legacy-' . $registration->id);

        return view('registrations.my', compact('purchases'));
    }

    public function show(Registration $registration)
    {
        if ($registration->user_id !== Auth::id()) {
            abort(403);
        }

        $registration->load(['event', 'seat', 'order']);
        $registrations = $registration->order_id
            ? Registration::where('user_id', Auth::id())
            ->where('order_id', $registration->order_id)
            ->with(['event', 'seat', 'order'])
                ->get()
            : collect([$registration]);

        return view('registrations.show', compact('registration', 'registrations'));
    }

}
