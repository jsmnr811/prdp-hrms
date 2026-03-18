<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'office_id',
        'component_id',
        'unit_id',
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

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}