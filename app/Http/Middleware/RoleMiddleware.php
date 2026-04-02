<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Check agar user login nahi hai
        if (!Auth::check()) {
            return redirect('login');
        }

        // Check agar login user ka role match nahi karta
        // (Yeh farz karte hue ke aapke users table mein 'role' ka column hai)
        if (Auth::user()->role !== $role) {
            abort(403, 'Unauthorized Action. You do not have permission to access this page.');
        }

        return $next($request);
    }
}