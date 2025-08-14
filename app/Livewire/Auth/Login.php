<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    protected $messages = [
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 6 karakter.',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            
            $this->dispatch('login-success');
            
            // Redirect based on user role
            $user = Auth::user();
            if ($user->hasRole('admin')) {
                return $this->redirect(route('dashboard'), navigate: true);
            } elseif ($user->hasRole('guru')) {
                return $this->redirect(route('dashboard'), navigate: true);
            } elseif ($user->hasRole('siswa')) {
                return $this->redirect(route('dashboard'), navigate: true);
            } elseif ($user->hasRole('tata_usaha')) {
                return $this->redirect(route('dashboard'), navigate: true);
            } else {
                // Default redirect for users without specific roles
                return $this->redirect(route('dashboard'), navigate: true);
            }
        }

        throw ValidationException::withMessages([
            'email' => 'Email atau password tidak valid.',
        ]);
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('layouts.main', [
                'title' => 'Login - DigiClass'
            ]);
    }
}