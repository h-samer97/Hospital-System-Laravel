<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'role' => ['nullable', 'string', 'in:user,admin,doctor,ray_employee,laboratorie_employee'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $role = $this->input('role');
        $guard = $role === 'admin' ? 'admin' : 'web';

        \Log::info('LoginRequest authenticate', [
            'email' => $this->input('email'),
            'role' => $role,
            'guard' => $guard,
            'credentials' => $this->only('email', 'password')
        ]);

        if (! Auth::guard($guard)->attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            \Log::error('Authentication failed', [
                'email' => $this->input('email'),
                'role' => $role,
                'guard' => $guard
            ]);
            
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        \Log::info('Authentication attempt successful', [
            'email' => $this->input('email'),
            'role' => $role,
            'guard' => $guard,
            'authenticated' => Auth::guard($guard)->check()
        ]);

        // Check role-based authentication if role is specified
        if ($role && Auth::guard($guard)->check()) {
            $user = Auth::guard($guard)->user();
            \Log::info('Checking user role', [
                'user_id' => $user->id ?? null,
                'user_type' => get_class($user),
                'hasRole_method' => method_exists($user, 'hasRole'),
                'required_role' => $role
            ]);
            
            if (method_exists($user, 'hasRole') && !$user->hasRole($role)) {
                \Log::error('Role verification failed', [
                    'user_id' => $user->id,
                    'required_role' => $role
                ]);
                
                Auth::guard($guard)->logout();
                RateLimiter::hit($this->throttleKey());
                
                throw ValidationException::withMessages([
                    'email' => 'هذا الحساب غير مسجل كـ ' . $this->getRoleLabel($role),
                ]);
            }
        }

        RateLimiter::clear($this->throttleKey());
    }

    private function getRoleLabel(string $role): string
    {
        $labels = [
            'user' => 'مريض',
            'admin' => 'مدير',
            'doctor' => 'دكتور',
            'ray_employee' => 'موظف أشعة',
            'laboratorie_employee' => 'موظف مختبر',
        ];

        return $labels[$role] ?? $role;
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
