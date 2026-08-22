<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;

class CheckinController extends Controller
{
    public function check($token)
    {
        $registration = Registration::where('ticket_token', $token)
            ->with('event', 'user')
            ->first();

        // 1. O bilhete não existe?
        if (!$registration) {
            return view('checkin.invalid');
        }

        // 2. O bilhete não está pago/confirmado?
        if ($registration->status !== 'confirmed') {
            return view('checkin.invalid');
        }

        // 3. O bilhete já foi picado?
        if ($registration->checked_in) {
            return view('checkin.used', compact('registration'));
        }
        
        // 4. Tudo OK! Marca a entrada da pessoa.
        $registration->update([
            'checked_in' => true,
            'checked_in_at' => now(),
        ]);

        return view('checkin.success', compact('registration'));
    }

    public function scanner()
    {
        return view('checkin.scanner');
    }
}