<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user has a role of 2 (client)
        if (auth()->user() && auth()->user()->role === 2) {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized.!'], 401);
    }
}
