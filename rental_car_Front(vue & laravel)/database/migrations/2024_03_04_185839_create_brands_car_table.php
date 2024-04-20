<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandsCarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   //tabela de marcas de carro
        Schema::create('brands_car', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30)->unique();
            $table->string('image', 100)->comment('brand logo');
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
        Schema::dropIfExists('brands_car');
    }
}
