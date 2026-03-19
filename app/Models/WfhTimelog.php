<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WfhTimelog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'time_in',
        'time_out',
        'latitude',
        'longitude',
        'image_path',
        'accomplishments',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'time_in' => 'datetime:H:i:s',
        'time_out' => 'datetime:H:i:s',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByDate($query, $date)
    {
        return $query->where('date', $date);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    // Accessors
    public function getTotalHoursAttribute(): ?float
    {
        if ($this->time_in && $this->time_out) {
            $timeIn = $this->time_in;
            $timeOut = $this->time_out;

            // Handle overnight shifts
            if ($timeOut < $timeIn) {
                $timeOut->addDay();
            }

            return $timeIn->diffInMinutes($timeOut) / 60;
        }

        return null;
    }

    public function getIsLocationRequiredAttribute(): bool
    {
        return filter_var(env('WFH_REQUIRE_LOCATION', false), FILTER_VALIDATE_BOOLEAN);
    }

    public function getIsImageRequiredAttribute(): bool
    {
        return filter_var(env('WFH_REQUIRE_IMAGE', false), FILTER_VALIDATE_BOOLEAN);
    }

    public function hasLocation(): bool
    {
        return !is_null($this->latitude) && !is_null($this->longitude);
    }

    public function hasImage(): bool
    {
        return !is_null($this->image_path);
    }
}
