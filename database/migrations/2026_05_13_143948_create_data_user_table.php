<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_create_data_user_table.php
public function up(): void
{
    Schema::create('data_user', function (Blueprint $table) {
        $table->id('user_id');
        $table->foreignId('pondok_id')->nullable()->constrained('pondok', 'pondok_id')->onDelete('set null');
        $table->foreignId('jenis_user_id')->nullable()->constrained('jenis_user', 'jenis_user_id')->onDelete('set null');
        $table->string('nama_lengkap');
        $table->string('nik', 16)->unique();
        $table->string('tempat_lahir');
        $table->string('alamat');
        $table->string('foto_ktp')->nullable(); // Menyimpan path file gambar/pdf
        $table->string('foto_kk')->nullable();  // Menyimpan path file gambar/pdf
        $table->string('surat_putusan')->nullable();
        $table->string('surat_keterangan_pimpinan_pondok')->nullable();
        $table->string('surat_keterangan_guru_mengaji')->nullable();
        $table->string('surat_keterangan_kecamatan')->nullable();
        $table->string('nomor_rekening');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_user');
    }
};
