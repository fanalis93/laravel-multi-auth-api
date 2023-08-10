<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dd(auth()->user());
        // Check if the user has a role of 3 (admin)
        if (auth()->user() && auth()->user()->role === 3) {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized.1'], 401);
    }
}
