<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_number',
        'first_name',
        'last_name',
        'middle_name',
        'middle_initial',
        'suffix',
        'contact_number',
        'email',
        'gender',
        'birth_date',
        'tin',
        'blood_type',
        'landbank_account',
        'height',
        'weight',
        'address',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_number',
        'image',
        'terms',
        'office_id',
        'unit_id',
        'position_id',
        'employment_status',
        'date_hired',
        'date_ended',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'date_hired' => 'date',
        'date_ended' => 'date',
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
        'terms' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function user()
    {
        return $this->hasOne(User::class, 'employee_number', 'employee_number');
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        $name = $this->first_name;

        // Always use middle name if available
        if ($this->middle_name) {
            $name .= ' ' . strtoupper(substr($this->middle_name, 0, 1)) . '.';
        }

        $name .= ' ' . $this->last_name;

        if ($this->suffix) {
            $name .= ', ' . $this->suffix;
        }

        return $name;
    }

    public function getFormattedEmployeeNumberAttribute(): string
    {
        return str_pad($this->employee_number, 4, '0', STR_PAD_LEFT);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('employment_status', 'Hired');
    }

    public function scopeResigned($query)
    {
        return $query->where('employment_status', 'Resigned');
    }

    public function scopeTerminated($query)
    {
        return $query->where('employment_status', 'Terminated');
    }

    public function scopeByOffice($query, $officeId)
    {
        return $query->where('office_id', $officeId);
    }
}
