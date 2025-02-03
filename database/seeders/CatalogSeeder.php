<?php

namespace Database\Seeders;

use App\Models\Catalog;
use Faker\Factory as faker;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 4; $i++) {
            Catalog::create([
                'name' => $faker->name,
            ]);
        }
    }
}
