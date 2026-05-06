<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $role = $request->input('role');
        $guard = $role === 'admin' ? 'admin' : 'web';
        
        // Debug logging
        \Log::info('Login attempt', [
            'email' => $request->input('email'),
            'role' => $role,
            'guard' => $guard
        ]);
        
        $request->authenticate();

        $request->session()->regenerate();

        // Debug logging after auth
        \Log::info('Authentication successful', [
            'email' => $request->input('email'),
            'role' => $role,
            'guard' => $guard,
            'authenticated' => Auth::guard($guard)->check()
        ]);

        // Redirect based on role
        if ($role === 'admin') {
            return redirect()->intended(route('dashboard.admin', absolute: false));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Handle an incoming admin authentication request.
     */
    public function adminStore(LoginRequest $request): RedirectResponse
    {
        $request->merge(['role' => 'admin']);
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
