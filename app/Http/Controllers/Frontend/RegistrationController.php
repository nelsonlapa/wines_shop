<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function my()
    {
        $registrationsByEvent = Registration::where('user_id', Auth::id())
            ->with(['event', 'seat'])
            ->latest()
            ->get()
            ->groupBy('event_id');

        return view('registrations.my', compact('registrationsByEvent'));
    }

    public function show(Registration $registration)
    {
        if ($registration->user_id !== Auth::id()) {
            abort(403);
        }

        $registration->load(['event', 'seat']);

        return view('registrations.show', compact('registration'));
    }

}
