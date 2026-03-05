<?php

namespace Database\Factories;

use App\Models\BeerType;
use Illuminate\Database\Eloquent\Factories\Factory;

class BeerTypeFactory extends Factory
{
    protected $model = BeerType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word().' Lager',
        ];
    }
}
