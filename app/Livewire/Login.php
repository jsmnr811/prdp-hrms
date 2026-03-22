<?php

namespace App\Livewire;

use App\Mail\TemporaryPassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
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

    public $forgotStatusVariant = null;

    public $showChangePasswordModal = false;

    public $newPassword = '';

    public $confirmPassword = '';

    public $maxResendReached = false;

    public $isMaxAttemptsMessage = false;

    public $userToChange = null;

    public function mount()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('administrator')) {
                return redirect()->to('/admin/dashboard');
            } else {
                return redirect()->to('/dashboard');
            }
        }
    }

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

        logger('Remember value:', ['remember' => $this->remember]);

        $remember = (bool) $this->remember;

        $employeeNumber = str_pad(trim($this->login), 4, '0', STR_PAD_LEFT);

        $user = User::where('employee_number', $employeeNumber)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'login' => 'Invalid credentials.',
            ]);
        }

        logger('User found: '.get_class($user).' id: '.$user->id);

        // Check active status
        if (! $user->isActive()) {
            throw ValidationException::withMessages([
                'login' => 'Account deactivated. Contact HR.',
            ]);
        }

        // Check if temporary password has expired
        if ($user->temp_password_expires_at && $user->temp_password_expires_at instanceof Carbon && $user->temp_password_expires_at->isPast()) {
            throw ValidationException::withMessages([
                'login' => 'Temporary password has expired. Please request a new one.',
            ]);
        }

        // Check if password change is required
        if ($user->mustChangePassword()) {
            $this->userToChange = $user;
            $this->showChangePasswordModal = true;

            return;
        }

        // Attempt login with remember option
        if (! Auth::attempt(['employee_number' => $employeeNumber, 'password' => $this->password], $remember)) {
            throw ValidationException::withMessages([
                'login' => 'Invalid credentials.',
            ]);
        }

        // Success - regenerate session
        session()->regenerate();

        // Update last login
        $user->updateLastLogin();

        // Redirect based on role
        if ($user->hasRole('administrator')) {
            return redirect()->to('/admin/dashboard');
        } elseif ($user->hasRole('employee')) {
            return redirect()->to('/dashboard');
        } else {
            return redirect()->to('/admin'); // fallback
        }
    }

    public function sendResetLink()
    {
        $this->validate([
            'forgotEmail' => ['required', 'email'],
        ]);

        $user = User::whereHas('employee', function ($q) {
            $q->where('email', $this->forgotEmail);
        })->first();

        if (! $user) {
            $this->addError('forgotEmail', 'No account found with this email address.');

            return;
        }

        $today = now()->toDateString();

        // Check daily reset attempts
        if ($user->password_reset_date?->toDateString() === $today) {
            // Same day - check attempts
            if ($user->password_reset_attempts >= 3) {
                $this->forgotStatus = 'Maximum resend attempts reached for today. Please contact the System administrator.';
                $this->forgotStatusVariant = 'danger';
                $this->maxResendReached = true;
                $this->isMaxAttemptsMessage = true;

                return;
            }
            $attempts = $user->password_reset_attempts + 1;
        } else {
            // New day - reset attempts
            $attempts = 1;
            $this->maxResendReached = false;
        }

        // Generate temporary password
        $temporaryPassword = Str::random(10);

        // Update user with hashed password, flag, and reset tracking
        $user->update([
            'password' => bcrypt($temporaryPassword),
            'must_change_password' => true,
            'temp_password_expires_at' => now()->addMinutes(30),
            'password_reset_attempts' => $attempts,
            'password_reset_date' => $today,
        ]);

        // Queue temporary password email
        Mail::to($this->forgotEmail)->queue(new TemporaryPassword($user, $temporaryPassword));
        $this->forgotStatus = 'Your temporary password has been queued for delivery!';
        $this->forgotStatusVariant = 'success';
        $this->isMaxAttemptsMessage = false;
    }

    public function resendResetLink()
    {
        $user = User::whereHas('employee', function ($q) {
            $q->where('email', $this->forgotEmail);
        })->first();

        if (! $user) {
            $this->addError('forgotEmail', 'No account found with this email address.');

            return;
        }

        $today = now()->toDateString();

        // Check daily reset attempts
        if ($user->password_reset_date?->toDateString() === $today) {
            // Same day - check attempts
            if ($user->password_reset_attempts >= 3) {
                $this->forgotStatus = 'Maximum resend attempts reached for today. Please contact the System administrator.';
                $this->forgotStatusVariant = 'danger';
                $this->maxResendReached = true;
                $this->isMaxAttemptsMessage = true;

                return;
            }
            $attempts = $user->password_reset_attempts + 1;
        } else {
            // New day - reset attempts
            $attempts = 1;
            $this->maxResendReached = false;
        }

        // Generate temporary password
        $temporaryPassword = Str::random(10);

        // Update user with hashed password, flag, and reset tracking
        $user->update([
            'password' => bcrypt($temporaryPassword),
            'must_change_password' => true,
            'temp_password_expires_at' => now()->addMinutes(30),
            'password_reset_attempts' => $attempts,
            'password_reset_date' => $today,
        ]);

        // Queue temporary password email
        Mail::to($this->forgotEmail)->queue(new TemporaryPassword($user, $temporaryPassword));
        $this->forgotStatus = 'Your temporary password has been queued for delivery!';
        $this->forgotStatusVariant = 'success';
    }

    public function changePassword()
    {
        logger('ChangePassword: Starting, session ID: '.session()->getId().', user authenticated: '.(Auth::check() ? 'yes' : 'no'));

        $this->validate([
            'newPassword' => ['required', 'string', 'min:8'],
            'confirmPassword' => ['required', 'same:newPassword'],
        ]);

        $user = $this->userToChange;

        if (! $user) {
            logger('ChangePassword: No user to change password for');

            return;
        }

        logger('ChangePassword: User ID: '.$user->id.', role: '.$user->roles->pluck('name')->join(', '));

        if (Hash::check($this->newPassword, $user->password)) {
            $this->addError('newPassword', 'New password must be different from the current password.');

            return;
        }

        $user->update([
            'password' => Hash::make($this->newPassword),
            'must_change_password' => false,
            'password_changed_at' => now(),
            'temp_password_expires_at' => null,
        ]);

        logger('ChangePassword: Password updated successfully');

        $this->showChangePasswordModal = false;
        $this->newPassword = '';
        $this->confirmPassword = '';
        $this->userToChange = null;

        // Refresh the page so user can login with new password
        $this->js('window.location.reload();');
    }

    public function togglePassword()
    {
        $this->showPassword = ! $this->showPassword;
    }

    public function render()
    {
        logger('Login render: Starting render');

        return view('livewire.login')
            ->layout('components.layouts.guest');
    }
}
