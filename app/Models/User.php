<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $role): bool
    {
        // For now, check if email contains role indicators
        // This is a temporary solution - you should implement proper role system
        $email = $this->email;
        
        return match($role) {
            'admin' => str_contains($email, 'admin') || $this->id === 1,
            'doctor' => str_contains($email, 'doctor') || str_contains($email, 'dr'),
            'ray_employee' => str_contains($email, 'ray') || str_contains($email, 'xray'),
            'laboratorie_employee' => str_contains($email, 'lab') || str_contains($email, 'laboratory'),
            'user' => true, // Default role for all users
            default => false,
        };
    }
}
