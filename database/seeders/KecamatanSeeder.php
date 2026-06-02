<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KecamatanSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Kecamatan::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            ['nama_kecamatan' => 'Banjarsari',    'created_at' => now(), 'updated_at' => now()],
            ['nama_kecamatan' => 'Jebres',         'created_at' => now(), 'updated_at' => now()],
            ['nama_kecamatan' => 'Laweyan',        'created_at' => now(), 'updated_at' => now()],
            ['nama_kecamatan' => 'Pasar Kliwon',   'created_at' => now(), 'updated_at' => now()],
            ['nama_kecamatan' => 'Serengan',       'created_at' => now(), 'updated_at' => now()],
        ];

        Kecamatan::insert($data);
    }
}