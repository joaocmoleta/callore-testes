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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->decimal('discount', 9, 2)->nullable(); // Desconto decimal
            $table->integer('discount_type')->nullable(); // Desconto percentual
            $table->integer('product')->nullable(); // Caso seja disconto do tipo 3 ou 4, precisa do produto
            $table->integer('valid'); // 0 - inativo 1-N - quantidade de usos -1 - ilimitado
            $table->dateTime('limit')->nullable(); // validade
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
};
