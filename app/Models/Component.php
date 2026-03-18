<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    // Relationships
    public function positions()
    {
        return $this->hasMany(Position::class);
    }
}