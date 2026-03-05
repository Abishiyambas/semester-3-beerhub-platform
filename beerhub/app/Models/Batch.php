<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'beer_type_id',
        'speed',
        'quantity',
        'defective',
        'avg_temperature',
        'avg_humidity',
        'avg_vibration',
        'preset_id',
        'user_id',
    ];

    public function beerType()
    {
        return $this->belongsTo(BeerType::class);
    }

    public function preset()
    {
        return $this->belongsTo(Preset::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
