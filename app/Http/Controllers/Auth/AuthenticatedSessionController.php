<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $request->session()->regenerate();

    $user = Auth::user();

    $role = strtolower((string) $user->role?->name);
    $intendedUrl = $request->session()->pull('url.intended');

    if (in_array($role, ['admin', 'organizador'], true)) {
        return redirect()->to($intendedUrl ?: '/welldone');
    }

    if ($intendedUrl && !preg_match('#/(welldone|dashboard|checkin)(?:/|$)#', parse_url($intendedUrl, PHP_URL_PATH) ?: '')) {
        return redirect()->to($intendedUrl);
    }

    return redirect()->route('home');
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
