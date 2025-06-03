<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is logged in and is an admin
        if (Auth::check() && Auth::user()->is_admin === true) {
            abort(403, 'Acesso não autorizado.');
        }

        return $next($request);
    }
}
