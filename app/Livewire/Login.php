<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Login extends Component
{
    public $login = '';
    public $password = '';
    public $remember = false;
    public $showPassword = false;
    public $showForgotModal = false;
    public $forgotEmail = '';
    public $forgotStatus = null;

    protected function rules()
    {
        return [
            'login' => ['required', 'string', 'min:1'],
            'password' => ['required', 'string', 'min:1'],
        ];
    }

    public function authenticate()
    {
    // dd('Login: Button clicked!');
        logger('Login attempt:', ['login' => $this->login]);

        $this->validate();

        $credentials = $this->getCredentials($this->login);
        $user = $this->findUser($this->login);

        if (!$user) {
            throw ValidationException::withMessages([
                'login' => 'Invalid credentials.',
            ]);
        }

        // Check active status
        if (!$user->isActive()) {
            throw ValidationException::withMessages([
                'login' => 'Account deactivated. Contact HR.',
            ]);
        }

        // Attempt authentication
        if (!Auth::attempt($credentials, $this->remember)) {
            throw ValidationException::withMessages([
                'login' => 'Invalid credentials.',
            ]);
        }

        // Success - regenerate session
        session()->regenerate();
        
        // Update last login
        $user->updateLastLogin();

        // Redirect
        return redirect()->to('/admin');
    }

    public function sendResetLink()
    {
        $this->validate([
            'forgotEmail' => ['required', 'email'],
        ]);

        $user = User::whereHas('employee', function ($q) {
            $q->where('email', $this->forgotEmail);
        })->first();

        if (!$user) {
            $this->addError('forgotEmail', 'No account found with this email address.');
            return;
        }

        $status = Password::sendResetLink(['email' => $this->forgotEmail]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->forgotStatus = 'We have emailed your password reset link!';
            $this->forgotEmail = '';
        } else {
            $this->addError('forgotEmail', 'Unable to send reset link.');
        }
    }

    public function togglePassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    private function getCredentials(string $input): array
    {
        $login = trim($input);

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            return ['email' => $login, 'password' => $this->password];
        }

        if (is_numeric($login)) {
            $employeeNumber = str_pad($login, 4, '0', STR_PAD_LEFT);
            return ['employee_number' => $employeeNumber, 'password' => $this->password];
        }

        return ['username' => $login, 'password' => $this->password];
    }

    private function findUser(string $input): ?User
    {
        $login = trim($input);

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            return User::whereHas('employee', fn($q) => $q->where('email', $login))->first();
        }

        if (is_numeric($login)) {
            $employeeNumber = str_pad($login, 4, '0', STR_PAD_LEFT);
            return User::where('employee_number', $employeeNumber)->first();
        }

        return User::where('username', $login)->first();
    }

    public function render()
    {
        return view('livewire.login')
            ->layout('components.layouts.guest');
    }
}