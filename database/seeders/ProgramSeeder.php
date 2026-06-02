<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('program')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = now();

        $data = [
            // ── Tahun 2025 ───────────────────────────────────────────────
            [
                
                'tahun'              => 2025,
                'status'             => 'active',
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
            [
                
                'tahun'              => 2025,
                'status'             => 'active',
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
            [
                
                'tahun'              => 2025,
                'status'             => 'active',
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
            [
               
                'tahun'              => 2025,
                'status'             => 'inactive',
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
            [
               
                'tahun'              => 2025,
                'status'             => 'active',
                'created_at'         => $now,
                'updated_at'         => $now,
            ],

            // ── Tahun 2026 ───────────────────────────────────────────────
            [
               
                'tahun'              => 2026,
                'status'             => 'active',
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
            [
               
                'tahun'              => 2026,
                'status'             => 'active',
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
            [
               
                'tahun'              => 2026,
                'status'             => 'active',
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
            [
               
                'tahun'              => 2026,
                'status'             => 'active',
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
            [
                
                'tahun'              => 2026,
                'status'             => 'active',
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
        ];

        Program::insert($data);
    }
}