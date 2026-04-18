<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->guard('admin')->attempt($request->only('email', 'password'))) {
            return redirect()->route('dashboard.admin');
        }

        return redirect()->back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    public function logout()
    {
        auth()->guard('admin')->logout();
        return redirect()->route('dashboard.admin');
    }

    public function store(AdminLoginRequest $request)
    {
        if ($request->authenticate()) {
            return redirect()->route('dashboard.admin');
        }

        return redirect()->back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    
}
