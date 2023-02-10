<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaledetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_detail', function (Blueprint $table) {
            $table->id('detail_sale');
            $table->string('det_jualfaktur')->nullable();
            $table->string('det_jualkodebarcode')->nullable();
            $table->string('det_hargajual')->nullable();
            $table->string('det_jualqty')->nullable();
            $table->string('det_jualtotal')->nullable();
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
        Schema::dropIfExists('saledetail');
    }
}
