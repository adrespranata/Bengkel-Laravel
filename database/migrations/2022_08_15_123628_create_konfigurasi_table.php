<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKonfigurasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('konfigurasi', function (Blueprint $table) {
            $table->id('konfigurasi_id');
            $table->string('nama_web')->nullable();
            $table->string('deskripsi')->nullable();
            $table->string('visi')->nullable();
            $table->string('misi')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('alamat')->nullable();
            $table->string('logo')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('konfigurasi');
    }
}
