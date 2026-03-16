<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in and is a customer
        if (Auth::check() && Auth::user()->role === 'customer') {
            return $next($request);
        }

        // If not a customer, redirect to home with error message
        return redirect('/')->with('error', 'Sie haben keine Berechtigung für diesen Bereich.');
    }
}
