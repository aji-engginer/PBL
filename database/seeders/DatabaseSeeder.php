<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            KecamatanSeeder::class, // harus pertama, admin & desa butuh kecamatan_id
            DesaSeeder::class,      // harus setelah kecamatan
            PondokSeeder::class,    // harus setelah desa
            AdminSeeder::class,     // harus setelah kecamatan
            ProgramSeeder::class,   // bebas, tidak ada dependensi
        ]);
    }
}