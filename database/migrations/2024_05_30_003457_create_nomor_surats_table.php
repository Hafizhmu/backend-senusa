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
        Schema::create('nomor_surats', function (Blueprint $table) {
            $table->id();
            $table->integer('id_desa');
            $table->integer('id_kecamatan');
            $table->integer('id_transaksi');
            $table->integer('id_dokumen');
            $table->integer('id_projek');
            $table->string('no_surat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nomor_surats');
    }
};
