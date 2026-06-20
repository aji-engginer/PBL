<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
   public function run(): void
{
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    DB::table('admin')->truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    $now = now();

    // Ambil kecamatan_id yang benar-benar ada di database
    $kecamatanIds = DB::table('kecamatan')->pluck('kecamatan_id')->toArray();

    // Kalau belum ada data kecamatan sama sekali, hentikan seeder
    if (empty($kecamatanIds)) {
        $this->command->warn('Kecamatan kosong! Jalankan KecamatanSeeder dulu.');
        return;
    }

    $data = [
        // Super Admin — tidak terikat kecamatan
        [
            'kecamatan_id' => null,
            'nama'         => 'Super Admin Kesra',
            'email'        => 'superadmin@kesra.id',
            'password'     => Hash::make('superadmin123'),
            'role'         => 'super admin',
            'created_at'   => $now,
            'updated_at'   => $now,
        ],
        // Admin Kecamatan — pakai ID yang benar-benar ada
        [
            'kecamatan_id' => $kecamatanIds[0] ?? null,
            'nama'         => 'Admin Kecamatan 1',
            'email'        => 'admin.kecamatan1@kesra.id',
            'password'     => Hash::make('admin123'),
            'role'         => 'admin',
            'created_at'   => $now,
            'updated_at'   => $now,
        ],
        [
            'kecamatan_id' => $kecamatanIds[1] ?? $kecamatanIds[0],
            'nama'         => 'Admin Kecamatan 2',
            'email'        => 'admin.kecamatan2@kesra.id',
            'password'     => Hash::make('admin123'),
            'role'         => 'admin',
            'created_at'   => $now,
            'updated_at'   => $now,
        ],
    ];

    // Insert super admin dulu sendiri (tanpa FK check)
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    Admin::insert($data);
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
}
}