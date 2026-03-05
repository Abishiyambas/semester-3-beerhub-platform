<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public function presets()
    {
        return $this->hasMany(Preset::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }
}
