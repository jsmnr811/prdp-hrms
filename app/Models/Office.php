<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    // Relationships
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function positions()
    {
        return $this->hasMany(Position::class);
    }
}