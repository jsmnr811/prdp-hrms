<?php

namespace App\Livewire\Employee;

use App\Mail\PasswordChanged;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class ChangePassword extends Component
{
    public $currentPassword = '';

    public $newPassword = '';

    public $confirmPassword = '';

    public $showCurrentPassword = false;

    public $showNewPassword = false;

    public $showConfirmPassword = false;

    public function getPasswordStrengthProperty()
    {
        $password = $this->newPassword;
        if (empty($password)) {
            return 0;
        }

        $strength = 0;

        // Length check
        if (strlen($password) >= 8) {
            $strength++;
        }

        // Uppercase check
        if (preg_match('/[A-Z]/', $password)) {
            $strength++;
        }

        // Lowercase check
        if (preg_match('/[a-z]/', $password)) {
            $strength++;
        }

        // Number check
        if (preg_match('/[0-9]/', $password)) {
            $strength++;
        }

        // Special character check
        if (preg_match('/[^A-Za-z0-9]/', $password)) {
            $strength++;
        }

        return min($strength, 4);
    }

    protected function rules()
    {
        $rules = [
            'newPassword' => ['required', 'string', 'min:8'],
            'confirmPassword' => ['required', 'same:newPassword'],
            'currentPassword' => ['required', 'string'],
        ];

        $user = Auth::user();
        if (! $user->must_change_password) {
            $rules['newPassword'][] = 'different:currentPassword';
        }

        return $rules;
    }

    public function confirmChangePassword()
    {
        $this->validate();
        LivewireAlert::title('Confirm Password Change')
            ->text('Are you sure you want to change your password?')
            ->question()
            ->timer(0)
            ->withConfirmButton('Yes, Change Password')
            ->withCancelButton('Cancel')
            ->onConfirm('confirmedChangePassword')
            ->show();
    }

    public function confirmedChangePassword()
    {
        $user = Auth::user();

        // Check if current password is correct
        $isValid = false;
        if (Hash::check($this->currentPassword, $user->password)) {
            $isValid = true;
        } elseif ($user->temp_password && Hash::check($this->currentPassword, $user->temp_password)) {
            $isValid = true;
        }

        if (! $isValid) {
            throw ValidationException::withMessages([
                'currentPassword' => 'The current password is incorrect.',
            ]);
        }

        // Update password
        $user->update([
            'password' => Hash::make($this->newPassword),
            'password_changed_at' => now(),
            'must_change_password' => false,
            'temp_password_expires_at' => null,
            'temp_password' => null,
        ]);

        // Send password changed notification email
        Mail::to($user->email)->send(new PasswordChanged($user));

        // Clear form
        $this->reset(['currentPassword', 'newPassword', 'confirmPassword']);

        // Flash success message
        session()->flash('success', 'Password changed successfully.');

        // Redirect to dashboard
        return redirect()->route('dashboard');
    }

    public function toggleCurrentPassword()
    {
        $this->showCurrentPassword = ! $this->showCurrentPassword;
    }

    public function toggleNewPassword()
    {
        $this->showNewPassword = ! $this->showNewPassword;
    }

    public function toggleConfirmPassword()
    {
        $this->showConfirmPassword = ! $this->showConfirmPassword;
    }

    public function render()
    {
        return view('livewire.employee.change-password')
            ->layout('components.layouts.app');
    }
}
