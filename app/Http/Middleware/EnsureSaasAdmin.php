<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSaasAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is not authenticated
        if (!Auth::check()) {
            return redirect()->route('saasAdmin.login');
        }

        // Check if user has the saasAdmin role
        if (Auth::user()->role !== 'saasAdmin') {
            Auth::logout();
            return redirect()->route('saasAdmin.login')->with('error', 'This account does not have administrator access.');
        }

        return $next($request);
    }
} 