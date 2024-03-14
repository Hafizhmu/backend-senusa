<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;

class ProjekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 3; $i++) {
            DB::table('projeks')->insert([
                'nama' => $faker->unique()->lastName(),
                'harga' => $faker->randomNumber()
            ]);
        }
    }
}
