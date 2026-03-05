<?php

namespace Database\Seeders;

use App\Models\BeerType;
use Illuminate\Database\Seeder;

class BeerTypeSeeder extends Seeder
{
    public $timestamps = false;

    public function run(): void
    {
        $types = [
            0 => 'Pilsner',
            1 => 'Wheat',
            2 => 'IPA',
            3 => 'Stout',
            4 => 'Ale',
            5 => 'Alcohol Free',
        ];

        foreach ($types as $id => $name) {
            BeerType::updateOrCreate(
                ['id' => $id],
                ['name' => $name],
            );
        }
    }
}
