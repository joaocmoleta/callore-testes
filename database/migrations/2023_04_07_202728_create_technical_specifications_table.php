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
        Schema::create('technical_specifications', function (Blueprint $table) {
            $table->id();
            $table->integer('product');
            $table->string('type')->nullable();
            $table->string('color')->nullable();
            $table->string('voltage')->nullable();
            $table->string('wattage')->nullable();
            $table->string('cable')->nullable();
            $table->string('material')->nullable();
            $table->string('paint')->nullable();
            $table->string('indicate')->nullable();
            $table->string('suporte_paredes')->nullable();
            $table->string('suporte_chao')->nullable();
            $table->string('sizes')->nullable();
            $table->string('pack_sizes')->nullable();
            $table->string('line')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('guarantee')->nullable();
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
        Schema::dropIfExists('technical_specifications');
    }
};
