<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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

        if (auth()->check() && $user->two_factor_code && !$user->two_factor_verified) {
            if (!$request->is('two-factor*')) {
                return redirect()->route('two-factor.index');
            }
        }
        return $next($request);
    }
}
