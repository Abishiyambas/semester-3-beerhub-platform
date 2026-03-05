<?php

namespace Database\Factories;

use App\Models\Batch;
use App\Models\BeerType;
use App\Models\Preset;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BatchFactory extends Factory
{
    protected $model = Batch::class;

    public function definition(): array
    {
        return [
            'beer_type_id' => BeerType::factory(),
            'speed' => $this->faker->numberBetween(50, 200),
            'quantity' => $this->faker->numberBetween(100, 1000),
            'defective' => $this->faker->numberBetween(0, 20),
            'avg_temperature' => $this->faker->randomFloat(2, 5, 25),
            'avg_humidity' => $this->faker->randomFloat(2, 20, 80),
            'avg_vibration' => $this->faker->randomFloat(2, 0, 10),
            'preset_id' => Preset::factory(),
            'user_id' => User::factory(),
        ];
    }
}
