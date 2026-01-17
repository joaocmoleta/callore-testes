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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('pay_id')->nullable();
            $table->double('amount')->nullable();
            $table->string('type')->nullable();
            $table->string('installments')->nullable();
            $table->string('cd_brand')->nullable();
            $table->string('cd_first_digits')->nullable();
            $table->string('cd_last_digits')->nullable();
            $table->string('cd_exp_month')->nullable();
            $table->string('cd_exp_year')->nullable();
            $table->string('cd_holder_name')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('street')->nullable();
            $table->string('number')->nullable();
            $table->string('complement')->nullable();
            $table->string('locality')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('region_code')->nullable();
            $table->string('country')->nullable();
            $table->string('boleto_id')->nullable();
            $table->text('links')->nullable();
            $table->text('barcode')->nullable();
            $table->string('due_date')->nullable();
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
        Schema::dropIfExists('payments');
    }
};
