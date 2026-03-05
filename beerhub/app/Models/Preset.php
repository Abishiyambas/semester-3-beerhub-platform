<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preset extends Model
{
    use HasFactory;

    // public $timestamps = false;

    protected $fillable = [
        'name',
        'beer_type_id',
        'speed',
        'quantity',
        'user_id',
    ];

    public function beerType()
    {
        return $this->belongsTo(BeerType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
