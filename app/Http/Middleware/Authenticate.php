<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // For API routes, we'll use JWT middleware instead
        // This is a placeholder that allows the application to boot properly
        
        // Check if user is authenticated via JWT token
        if (!$request->user()) {
            // Let the request pass for now - JWT middleware will handle validation
            // You can add more sophisticated logic here if needed
        }

        return $next($request);
    }
}
