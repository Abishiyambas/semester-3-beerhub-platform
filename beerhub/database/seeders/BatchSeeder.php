<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\BeerType;
use App\Models\Preset;
use App\Models\User;
use Illuminate\Database\Seeder;

class BatchSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $beerType = BeerType::first();
        $preset = Preset::first();

        if (! $user || ! $beerType || ! $preset) {
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            Batch::create([
                'beer_type_id' => $beerType->id,
                'speed' => 100 + $i,
                'quantity' => 500 + $i * 10,
                'defective' => rand(0, 10),
                'avg_temperature' => rand(5, 25),
                'avg_humidity' => rand(30, 80),
                'avg_vibration' => rand(0, 10),
                'preset_id' => $preset->id,
                'user_id' => $user->id,
            ]);
        }
    }
}
