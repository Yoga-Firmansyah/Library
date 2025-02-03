<?php

namespace Database\Seeders;

use App\Models\Author;
use Faker\Factory as faker;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            Author::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'phone_number' => '0812' . $faker->randomNumber(8),
                'address' => $faker->address,
            ]);
        }
    }
}
