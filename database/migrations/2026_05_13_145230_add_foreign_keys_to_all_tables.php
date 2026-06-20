<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // desa
        Schema::table('desa', function (Blueprint $table) {
            $table->foreign('kecamatan_id')->references('kecamatan_id')->on('kecamatan')->cascadeOnDelete();
        });

        // pondok
        Schema::table('pondok', function (Blueprint $table) {
            $table->foreign('kecamatan_id')->references('kecamatan_id')->on('kecamatan')->cascadeOnDelete();
            $table->foreign('desa_id')->references('desa_id')->on('desa')->cascadeOnDelete();
        });

        // admin
        Schema::table('admin', function (Blueprint $table) {
            $table->foreign('kecamatan_id')->references('kecamatan_id')->on('kecamatan')->nullOnDelete();
        });

        // data_user - foreign keys already defined in create_data_user_table migration


        // pengajuan
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->foreign('operator_id')->references('admin_id')->on('admin')->nullOnDelete();
            $table->foreign('user_id')->references('user_id')->on('data_user')->cascadeOnDelete();
            $table->foreign('admin_id')->references('admin_id')->on('admin')->nullOnDelete();
            $table->foreign('program_id')->references('program_id')->on('program')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->dropForeign(['operator_id', 'user_id', 'admin_id', 'program_id']);
        });
        Schema::table('admin', function (Blueprint $table) {
            $table->dropForeign(['kecamatan_id']);
        });
        Schema::table('pondok', function (Blueprint $table) {
            $table->dropForeign(['kecamatan_id', 'desa_id']);
        });
        Schema::table('desa', function (Blueprint $table) {
            $table->dropForeign(['kecamatan_id']);
        });
    }
};
