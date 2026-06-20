<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_user', function (Blueprint $table) {
            $table->id('user_id');
            $table->foreignId('pondok_id')->nullable()->constrained('pondok', 'pondok_id')->onDelete('set null');
            $table->string('nama_lengkap');
            $table->string('nik', 16)->unique();
            $table->string('tempat_lahir');
            $table->string('alamat');
            $table->string('foto_ktp')->nullable();
            $table->string('foto_kk')->nullable();
            $table->string('surat_putusan')->nullable();
            $table->string('surat_keterangan_pimpinan_pondok')->nullable();
            $table->string('surat_keterangan_guru_mengaji')->nullable();
            $table->string('surat_keterangan_kecamatan')->nullable();
            $table->string('nomor_rekening');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_user');
    }
};