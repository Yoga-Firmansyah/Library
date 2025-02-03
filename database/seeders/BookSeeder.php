<?php

namespace Database\Seeders;

use App\Models\Book;
use Faker\Factory as faker;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            Book::create([
                'isbn' => $faker->randomNumber(9, true),
                'title' => $faker->name,
                'year' => rand(2010, 2024),
                'publisher_id' => rand(1, 20),
                'author_id' => rand(1, 20),
                'catalog_id' => rand(1, 4),
                'qty' => rand(10, 20),
                'price' => rand(10000, 200000),
            ]);
        }
    }
}
