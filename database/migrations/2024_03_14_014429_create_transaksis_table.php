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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->integer('id_projek');
            $table->integer('id_desa');
            $table->double('harga');
            $table->boolean('status_kontrak');
            $table->boolean('status_pembayaran');
            $table->date('tanggal_pembayaran');
            $table->date('tanggal_transaksi');
            $table->integer('id_perusahaan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
