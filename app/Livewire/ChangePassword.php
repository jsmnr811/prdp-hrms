<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class ChangePassword extends Component
{
    public $currentPassword = '';
    public $newPassword = '';
    public $confirmPassword = '';

    public $tempUser;

    public function getCurrentUser()
    {
        return Auth::check() ? Auth::user() : $this->tempUser;
    }

    public function mount()
    {
        if (!Auth::check()) {
            if (session('temp_user_id')) {
                $this->tempUser = User::find(session('temp_user_id'));
                if (!$this->tempUser) {
                    return redirect()->route('login');
                }
            } else {
                return redirect()->route('login');
            }
        }
    }

    protected function rules()
    {
        $rules = [
            'newPassword' => ['required', 'string', 'min:8'],
            'confirmPassword' => ['required', 'same:newPassword'],
            'currentPassword' => ['required', 'string'],
        ];

        if (!$this->getCurrentUser()->must_change_password) {
            $rules['newPassword'][] = 'different:currentPassword';
        }

        return $rules;
    }

    public function changePassword()
    {
        $this->validate();

        $user = $this->getCurrentUser();

        // Check if current password is correct
        $isValid = false;
        if (Hash::check($this->currentPassword, $user->password)) {
            $isValid = true;
        } elseif ($user->temp_password && Hash::check($this->currentPassword, $user->temp_password)) {
            $isValid = true;
        }

        if (!$isValid) {
            throw ValidationException::withMessages([
                'currentPassword' => 'The current password is incorrect.',
            ]);
        }

        // Check if this is a temp password change
        $isTempPasswordChange = !is_null($user->temp_password);

        // Update password
        $user->update([
            'password' => Hash::make($this->newPassword),
            'password_changed_at' => now(),
            'must_change_password' => false,
            'temp_password_expires_at' => null,
            'temp_password' => null,
        ]);
        if ($isTempPasswordChange) {
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'change_password',
                'description' => 'Changed password (temporary)',
                'ip_address' => request()->ip(),
            ]);
        }
        // Clear form
        $this->reset(['currentPassword', 'newPassword', 'confirmPassword']);

        // Flash success message
        session()->flash('success', 'Password changed successfully.');

        if ($isTempPasswordChange) {
            // Logout the user for temp password change
            Auth::logout();

            // Redirect to login
            return redirect()->route('login');
        } else {
            // For regular password change, stay logged in and redirect to dashboard
            if ($user->hasRole('administrator')) {
                return redirect()->to('/admin/dashboard');
            } elseif ($user->hasRole('employee')) {
                return redirect()->to('/dashboard');
            } else {
                return redirect()->to('/admin'); // fallback
            }
        }
    }

    public function render()
    {
        $user = $this->getCurrentUser();

        return view('livewire.change-password', compact('user'))
            ->layout('components.layouts.guest');
    }
}
