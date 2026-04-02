<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeCategory extends Model
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

    public function offices()
    {
        return $this->hasMany(Office::class);
    }
}
