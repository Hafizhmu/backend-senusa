<?php

namespace Database\Seeders;

use Faker\Factory as Faker ;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DesaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            DB::table('desas')->insert([
                'Nama_Desa' => $faker->firstName,
                'Nama_Kades' => $faker->name,
                'id_kabupaten' => $faker->numberBetween(1,20),
                'id_kecamatan' => $faker->numberBetween(1,20),
                'alamat' => $faker->address(),
                'telepon' => $faker->randomNumber()
            ]);
        }
    }
}
