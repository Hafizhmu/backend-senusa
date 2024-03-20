<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KontrakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            DB::table('kontraks')->insert([
                'nama_kontrak' => 'Kontrak '. $faker->country(),
                'id_desa' => $faker->unique()->numberBetween(1,20),
                'id_cv' => $faker->numberBetween(1,3),
                'tanggal' => $faker->dateTimeThisMonth(),
                'nominal' => $faker->randomNumber()
            ]);
        }
    }
}

