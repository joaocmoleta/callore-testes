<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('safra_pedidos', function (Blueprint $table) {
            $table->id();
            $table->integer('order');
            $table->string('charge_id');
            $table->string('aditumNumber');
            $table->text('qrCode');
            $table->text('qrCodeImage');
            $table->string('transactionId');
            $table->string('paymentType');
            $table->string('amount');
            $table->string('acquirer');
            $table->dateTime('creationDateTime');
            $table->string('nsu');
            $table->timestamps();
            $table->softDeletes();
            // $table->dropSoftDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('safra_pedidos', function(Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
