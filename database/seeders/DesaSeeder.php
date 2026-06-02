<?php

namespace Database\Seeders;

use App\Models\Desa;
use Illuminate\Database\Seeder;

class DesaSeeder extends Seeder
{
    public function run(): void
    {
        Desa::create([
            'kecamatan_id' => 1,
            'nama_desa' => 'Desa Pajang'
        ]);

        Desa::create([
            'kecamatan_id' => 2,
            'nama_desa' => 'Desa Gayan'
        ]);
    }
}