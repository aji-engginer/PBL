<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->id('pengajuan_id');
            $table->unsignedBigInteger('operator_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('program_id');
            $table->enum('status_pengajuan', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->date('tanggal_pengajuan')->nullable();
            $table->date('tanggal_validasi')->nullable();
            $table->text('catatan_pengajuan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};
