<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisUser;

class JenisUserSeeder extends Seeder
{

    public function run(): void
    {
    JenisUser::create(['nama_jenis_user' => 'Ustadz / Ustadzah']);
    JenisUser::create(['nama_jenis_user' => 'Guru Madrasah']);
    JenisUser::create(['nama_jenis_user' => 'Staf Administrasi']);
    }
}
