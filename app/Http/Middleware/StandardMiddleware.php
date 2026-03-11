<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class StandardMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'standard') {
            return $next($request);
        }

        return redirect()->route('login')->withErrors("Accès refusé !");
    }
}
