<?php

namespace App\Http\Controllers;

use App\Notifications\TwoFactorCode;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TwoFactorController extends Controller
{
    public function index()
    {
        return Inertia::render('TwoFactor/Index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric|digits:6',
        ]);

        $user = auth()->user();

        if ($request->input('code') !== $user->two_factor_code) {
            return back()->withErrors([
                'code' => 'The provided code is incorrect.',
            ]);
        }

        if ($user->two_factor_expires_at < now()) {
            return back()->withErrors([
                'code' => 'The code has expired. Please request a new one.',
            ]);
        }

        $user->resetTwoFactorCode();

        return redirect()->intended(route('dashboard'));
    }

    public function resend()
    {
        $user = auth()->user();
        $user->generateTwoFactorCode();
        $user->notify(new TwoFactorCode());

        return back()->with('status', 'We have sent you a fresh verification code!');
    }
}
