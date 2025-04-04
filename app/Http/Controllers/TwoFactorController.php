<?php

namespace App\Http\Controllers;

use App\Notifications\TwoFactorCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class TwoFactorController extends Controller
{
    public function index()
    {
        // Check if the user is authenticated and has a two-factor code
        if (!Auth::check() || !Auth::user()->two_factor_code) {
            return redirect()->route('login');
        }
        
        return Inertia::render('TwoFactor/Index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = auth()->user();

        // Ensure we're comparing strings to avoid type issues
        if ((string)$request->input('code') !== (string)$user->two_factor_code) {
            return back()->withErrors([
                'code' => 'The provided code is incorrect.',
            ]);
        }

        if ($user->two_factor_expires_at < now()) {
            return back()->withErrors([
                'code' => 'The code has expired. Please request a new one.',
            ]);
        }

        // Mark as verified and clear the code
        $user->resetTwoFactorCode();
        
        // Store verification status in session
        Session::put('two_factor_verified', true);
        
        // Redirect to dashboard
        return redirect()->intended(route('dashboard'));
    }

    public function resend()
    {
        $user = auth()->user();
        
        // Generate a new code
        $user->generateTwoFactorCode();
        $user->notify(new TwoFactorCode());

        return back()->with('status', 'We have sent you a fresh verification code!');
    }
}
