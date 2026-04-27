<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Login extends Component
{
    public $login = '';

    public $password = '';

    public $remember = false;

    public $showPassword = false;

    public function mount()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->can('view-admin-dashboard')) {
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
        $this->validate();

        $remember = (bool) $this->remember;

        $employeeNumber = str_pad(trim($this->login), 4, '0', STR_PAD_LEFT);

        $user = User::where('employee_number', $employeeNumber)->first();

        if (! $user) {
            // Log failed login attempt - user not found
            ActivityLog::create([
                'action' => 'login_failed',
                'description' => 'Login attempt with invalid employee number: '.$employeeNumber,
                'ip_address' => request()->ip(),
            ]);

            throw ValidationException::withMessages([
                'login' => 'Invalid credentials.',
            ]);
        }

        // Check active status
        if (! $user->isActive()) {
            // Log failed login attempt - inactive account
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'login_failed',
                'description' => 'Login attempt for deactivated account',
                'ip_address' => request()->ip(),
            ]);

            throw ValidationException::withMessages([
                'login' => 'Account deactivated. Contact HR.',
            ]);
        }

        /**
         * ✅ TEMP PASSWORD LOGIN → REDIRECT ONLY (NO AUTH)
         */
        if (
            $user->temp_password &&
            Hash::check($this->password, $user->temp_password)
        ) {
            if ($user->temp_password_expires_at && $user->temp_password_expires_at->isPast()) {
                // Log failed login attempt - expired temp password
                ActivityLog::create([
                    'user_id' => $user->id,
                    'action' => 'login_failed',
                    'description' => 'Login attempt with expired temporary password',
                    'ip_address' => request()->ip(),
                ]);

                throw ValidationException::withMessages([
                    'login' => 'Temporary password has expired. Please request a new one.',
                ]);
            }

            // Log successful temp password login
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'temp_password_login',
                'description' => 'User logged in with temporary password',
                'ip_address' => request()->ip(),
            ]);

            session(['temp_user_id' => $user->id]);

            return redirect()->to('/change-password');
        }

        /**
         * ✅ ORIGINAL PASSWORD LOGIN → ALLOW EVEN IF NOT CHANGED
         */
        if (! Hash::check($this->password, $user->password)) {
            // Log failed login attempt - incorrect password
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'login_failed',
                'description' => 'Login attempt with incorrect password',
                'ip_address' => request()->ip(),
            ]);

            throw ValidationException::withMessages([
                'login' => 'Invalid credentials.',
            ]);
        }

        // Authenticate user
        Auth::login($user, $remember);
        session()->regenerate();
        session()->save();

        // Log login activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'login',
            'description' => 'User logged in successfully with employee number: '.$user->employee_number,
            'ip_address' => request()->ip(),
        ]);

        $user->updateLastLogin();

        // Redirect based on role

        if ($user->can('view-admin-dashboard')) {
            return redirect()->to('/admin/dashboard');
        } else {
            return redirect()->to('/dashboard');
        }

        return redirect()->to('/admin'); // fallback
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
