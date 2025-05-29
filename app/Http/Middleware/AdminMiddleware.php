<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is logged in and is an admin
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Acesso não autorizado. Apenas administradores.');
        }

        return $next($request);
    }
}
