<?php

namespace Database\Factories;

use App\Models\BeerType;
use App\Models\Preset;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PresetFactory extends Factory
{
    protected $model = Preset::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word().' preset',
            'beer_type_id' => BeerType::factory(),
            'speed' => $this->faker->numberBetween(50, 200),
            'quantity' => $this->faker->numberBetween(100, 1000),
            'user_id' => User::factory(),
        ];
    }
}
