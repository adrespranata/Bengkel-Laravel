<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasetempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_temp', function (Blueprint $table) {
            $table->id('purchase_det');
            $table->string('det_belifaktur')->nullable();
            $table->string('det_belikodebarcode')->nullable();
            $table->string('det_hargabeli')->nullable();
            $table->string('det_beliqty')->nullable();
            $table->string('det_belitotal')->nullable();
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
        Schema::dropIfExists('purchasetemp');
    }
}
