<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
{
    // Check if user is logged in AND has the correct role
    if (!$request->user() || $request->user()->role !== $role) {
        // Send them back or to a 'not authorized' page
        abort(403, 'Unauthorized action.');
    }

    return $next($request);
}
}
