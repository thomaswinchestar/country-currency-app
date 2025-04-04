<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Skip two-factor check if session has verification flag
        if (Session::has('two_factor_verified') && Session::get('two_factor_verified') === true) {
            return $next($request);
        }

        // Only redirect if all these conditions are true:
        // 1. User is authenticated
        // 2. User has a two_factor_code that's not null
        // 3. User is not verified (two_factor_verified is false)
        // 4. The request is not already for the two-factor routes
        if (auth()->check() && 
            $user->two_factor_code && 
            $user->two_factor_verified === false) {
                
            if (!$request->is('two-factor*') && !$request->is('logout')) {
                return redirect()->route('two-factor.index');
            }
        }
        
        return $next($request);
    }
}
