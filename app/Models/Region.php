<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = ['cluster_id', 'name', 'description'];

    protected $table = 'regions';

    public function cluster()
    {
        return $this->belongsTo(Cluster::class);
    }
}
