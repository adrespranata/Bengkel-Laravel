<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale', function (Blueprint $table) {
            $table->id('jual_faktur');
            $table->string('pelanggan_id')->nullable();
            $table->string('jual_date')->nullable();
            $table->string('jual_dispersen')->nullable();
            $table->string('jual_disuang')->nullable();
            $table->string('jual_total')->nullable();
            $table->string('jual_totalbersih')->nullable();
            $table->string('jual_jmluang')->nullable();
            $table->string('jual_sisauang')->nullable();
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
        Schema::dropIfExists('sale');
    }
}
