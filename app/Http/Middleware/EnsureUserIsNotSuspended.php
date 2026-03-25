<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsNotSuspended
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->is_suspended) {
            Auth::logout();

            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Votre compte a été suspendu. Veuillez contacter un administrateur.',
                ]);
        }

        return $next($request);
    }
}