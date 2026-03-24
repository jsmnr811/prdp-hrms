<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'employee_number',
        'username',
        'password',
        'status',
        'email_verified_at',
        'password_changed_at',
        'must_change_password',
        'temp_password_expires_at',
        'last_login_at',
        'last_login_ip',
        'password_reset_attempts',
        'password_reset_date',
        'remember_token',
        'temp_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'temp_password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password_changed_at' => 'datetime',
        'temp_password_expires_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password_reset_date' => 'date',
    ];

    // Don't cast status as boolean - check it directly
    public function isActive(): bool
    {
        return (int) $this->status === 1;
    }

    // Relationships
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_number', 'employee_number');
    }

    public function updateLastLogin(): void
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
        ]);
    }

    public function getNameAttribute(): ?string
    {
        return $this->employee?->full_name ?? $this->username;
    }

    public function getEmailAttribute(): ?string
    {
        return $this->employee?->email;
    }

    public function passwordExpired(): bool
    {
        if (! $this->password_changed_at) {
            return false; // Don't force change on first login
        }

        return $this->password_changed_at->diffInDays(now()) > 90;
    }

    public function mustChangePassword(): bool
    {
        return (int) $this->must_change_password === 1;
    }

    public function getAuthPassword()
    {
        return $this->password;
    }
}
