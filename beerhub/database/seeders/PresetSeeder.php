<?php

namespace Database\Seeders;

use App\Models\BeerType;
use App\Models\Preset;
use App\Models\User;
use Illuminate\Database\Seeder;

class PresetSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $beerTypes = BeerType::all();

        if ($users->isEmpty() || $beerTypes->isEmpty()) {
            return;
        }

        foreach ($beerTypes as $beerType) {
            for ($i = 1; $i <= 3; $i++) {
                $randomUser = $users->random();

                Preset::create([
                    'name' => "{$beerType->name} preset {$i}",
                    'beer_type_id' => $beerType->id,
                    'speed' => 100 + $i * 10,
                    'quantity' => 500 + $i * 50,
                    'user_id' => $randomUser->id,
                ]);
            }
        }
    }
}
