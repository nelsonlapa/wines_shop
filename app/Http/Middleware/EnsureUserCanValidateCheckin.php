<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserCanValidateCheckin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->role) {
            abort(403, 'Acesso não autorizado.');
        }

        if (! in_array($user->role->name, ['Admin', 'Organizador'])) {
            abort(403, 'Apenas administradores ou organizadores podem validar QR Codes.');
        }
        return $next($request);
    }
}
