<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PerusahaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 3; $i++) {
            DB::table('perusahaans')->insert([
                'nama_perusahaan' => 'Cv. ' . $faker->company(),
                'nama_direktur' => $faker->name(),
                'format_nomor_surat' => $faker->numerify() . '/' . $faker->regexify('[A-Za-z]{3}') . '/' . '2024'
            ]);
        }
    }
}
