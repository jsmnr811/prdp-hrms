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
        'office_category_id',
        'cluster_id',
        'region_id',
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

    public function officeCategory()
    {
        return $this->belongsTo(OfficeCategory::class);
    }

    public function cluster()
    {
        return $this->belongsTo(Cluster::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
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

    public function scopeVisibleTo($query, $user)
    {
        // ✅ admin → see everything
        if ($user->hasRole('administrator')) {
            return $query;
        }

        $employee = $user->employee;

        // If no permission or no employee record → return none (safe fallback)
        if (!$user->can('view-employees') || !$employee) {
            return $query->whereRaw('1 = 0');
        }

        return $query
            ->where('office_category_id', $employee->office_category_id)
            ->when($employee->office_category_id == 2, function ($q) use ($employee) {
                $q->where('cluster_id', $employee->cluster_id);
            })
            ->when($employee->office_category_id == 3, function ($q) use ($employee) {
                $q->where('region_id', $employee->region_id);
            });
    }
}
