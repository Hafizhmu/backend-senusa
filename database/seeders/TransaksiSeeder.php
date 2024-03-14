<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            DB::table('transaksis')->insert([
                'id_projek' => $faker->numberBetween(1, 3),
                'id_desa' => $faker->numberBetween(1, 20),
                'id_kecamatan' => $faker->numberBetween(1, 20),
                'id_kabupaten' => $faker->numberBetween(1, 20)
            ]);
        }
    }
}
