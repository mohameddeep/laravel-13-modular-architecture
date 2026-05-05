<?php

namespace App\Modules\Auth\Http\Services\Dashboard\Auth;

use App\Modules\Auth\Http\Requests\Dashboard\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardAuthService
{
    public function showLoginForm(): View
    {
        return view('auth::dashboard.auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $remember    = (bool) $request->boolean('remember');

        if (! Auth::guard('admin')->attempt($credentials, $remember)) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', __('dashboard.invalid_credentials'));
        }

        $request->session()->regenerate();

        return redirect()
            ->intended(route('dashboard.home'))
            ->with('success', __('dashboard.welcome_back'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('dashboard.auth.login');
    }
}
