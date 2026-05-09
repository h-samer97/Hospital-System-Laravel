<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    // الحقول المشتركة بين كل الأدوار
    private array $rules = [
        'email'    => 'required|email',
        'password' => 'required|string',
        'role'     => 'required|string',
    ];

    // ===== مريض =====
    public function loginUser(Request $request)
    {
        return $this->attemptLogin($request, 'web', '/dashboard/user');
    }

    // ===== مشرف =====
    public function loginAdmin(Request $request)
    {
        return $this->attemptLogin($request, 'web', '/dashboard/admin');
    }

    // ===== دكتور =====
    public function loginDoctor(Request $request)
    {
        return $this->attemptLogin($request, 'web', '/dashboard/doctor');
    }

    // ===== موظف أشعة =====
    public function loginRayEmployee(Request $request)
    {
        return $this->attemptLogin($request, 'web', '/dashboard/ray');
    }

    // ===== موظف صيدلية =====
    public function loginPharmacyEmployee(Request $request)
    {
        return $this->attemptLogin($request, 'web', '/dashboard/pharmacy');
    }

    // ===== موظف مختبر =====
    public function loginLabEmployee(Request $request)
    {
        return $this->attemptLogin($request, 'web', '/dashboard/lab');
    }

    // ===== الدالة المشتركة =====
    private function attemptLogin(Request $request, string $guard, string $redirectTo)
    {
        $request->validate($this->rules);

        $credentials = $request->only('email', 'password');

        if (!Auth::guard($guard)->attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
            ]);
        }

        $request->session()->regenerate();

        return response()->json([
            'redirect' => $redirectTo,
        ]);
    }
}