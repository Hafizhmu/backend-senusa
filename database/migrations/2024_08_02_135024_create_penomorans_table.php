<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenomoransTable extends Migration
{
    public function up()
    {
        Schema::create('penomorans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('current_number');
            $table->integer('last_number');
            $table->string('format_penomoran');
            $table->unsignedBigInteger('id_kabupaten')->nullable();
            $table->unsignedBigInteger('id_kecamatan')->nullable();
            $table->unsignedBigInteger('id_projek')->nullable();
            $table->unsignedBigInteger('id_perusahaan')->nullable();
            $table->unsignedBigInteger('id_dokumen')->nullable();
            $table->integer('bulan');
            $table->integer('tahun');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penomorans');
    }
}
