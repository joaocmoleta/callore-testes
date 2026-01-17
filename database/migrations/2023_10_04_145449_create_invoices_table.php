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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('order')->nullable();
            $table->string('numero')->nullable();
            $table->string('serie')->nullable();
            $table->string('data_emissao')->nullable();
            $table->string('natureza')->nullable();
            $table->string('total')->nullable();
            $table->string('produto')->nullable();
            $table->string('chave')->nullable();
            $table->text('file')->nullable();
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
        Schema::dropIfExists('invoices');
    }
};
