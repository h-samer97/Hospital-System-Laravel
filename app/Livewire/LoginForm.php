<?php

namespace App\Livewire;

use App\Http\Requests\Auth;
use Livewire\Component;

class LoginForm extends Component
{
    public $email = '';

    public $password = '';

    protected $rules = ['email' => 'required|email', 'password' => 'required|min:6|max:10'];

    protected $messages = [
        'email.required' => 'Email Field is Required',
        'email.email' => 'Email NOT be Invalid',
        'password.required' => 'Password Field is Required',
        'password.min' => 'Password is < 6',
        'password.max' => 'Password is > 10',
    ];

    public function login()
    {

        if (Auth::attmpt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();

            return view('dashboard.user.dashboard');
        }

    }

    public function render()
    {
        return view('livewire.login-form');
    }
}
