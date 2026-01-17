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
        Schema::create('pagarme_pedidos', function (Blueprint $table) {
            $table->id();
            $table->integer('order');
            $table->string('id_order')->nullable();
            $table->string('code')->nullable();
            $table->boolean('closed')->nullable();
            $table->dateTime('pg_created_at')->nullable();
            $table->dateTime('pg_updated_at')->nullable();
            $table->dateTime('pg_closed_at')->nullable();
            $table->string('charge_id')->nullable();
            $table->string('charge_code')->nullable();
            $table->string('gateway_id')->nullable();
            $table->string('paid_amount')->nullable();
            $table->string('qr_code')->nullable();
            $table->string('qr_code_url')->nullable();
            $table->string('expires_at')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('antifraud_status')->nullable();
            $table->string('antifraud_score')->nullable();
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
        Schema::dropIfExists('pagarme_pedidos');
    }
};
