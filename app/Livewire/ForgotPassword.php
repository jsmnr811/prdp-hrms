<?php

namespace App\Livewire;

use App\Mail\TemporaryPassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;

class ForgotPassword extends Component
{
    public $forgotEmail = '';

    public $forgotStatus = null;

    public $forgotStatusVariant = null;

    public $maxResendReached = false;

    public $isMaxAttemptsMessage = false;

    public $resetSentAt = null;

    public $countdown = 0;

    public function mount()
    {
        if ($this->resetSentAt) {
            $this->countdown = max(0, 300 - now()->diffInSeconds($this->resetSentAt));
        }
    }

    public function updateCountdown()
    {
        if ($this->countdown > 0) {
            $this->countdown--;
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

        // Update user with temp password, flag, and reset tracking
        $user->update([
            'temp_password' => bcrypt($temporaryPassword),
            'must_change_password' => true,
            'temp_password_expires_at' => now()->addMinutes(30),
            'password_reset_attempts' => $attempts,
            'password_reset_date' => $today,
        ]);

        // Queue temporary password email
        Mail::to($this->forgotEmail)->queue(new TemporaryPassword($user, $temporaryPassword));
        $this->forgotStatus = 'A temporary password has been sent to your email. Please check your inbox, including your spam or junk folder. This password will expire in 30 minutes.';
        $this->forgotStatusVariant = 'success';
        $this->isMaxAttemptsMessage = false;
        $this->resetSentAt = now();
        $this->countdown = 300;
    }

    public function render()
    {
        return view('livewire.forgot-password')
            ->layout('components.layouts.guest');
    }
}
