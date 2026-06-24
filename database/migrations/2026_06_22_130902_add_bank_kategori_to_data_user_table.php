<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_user', function (Blueprint $table) {
            $table->string('bank')->nullable()->after('nomor_rekening');
            $table->enum('kategori', ['Guru Mengaji', 'Pimpinan Pondok'])->nullable()->after('bank');
        });
    }

    public function down(): void
    {
        Schema::table('data_user', function (Blueprint $table) {
            $table->dropColumn(['bank', 'kategori']);
        });
    }
};