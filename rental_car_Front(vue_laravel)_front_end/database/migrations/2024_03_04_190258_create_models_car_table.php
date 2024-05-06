<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelsCarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   //tabela de modelos de carro com numero de portas, número de assentos etc..
        Schema::create('models_car', function (Blueprint $table) {
            $table->id();
            //chave foreign : uma marca possui varias modelos mas uma modelo está relacionado a uma marca
            $table->unsignedBigInteger('brand_id');
            $table->string('name', 30);
            $table->string('image', 100);
            $table->integer('number_doors');
            $table->integer('seats');
            $table->boolean('air_bag');
            $table->boolean('abs');
            $table->timestamps();

            //foreign key (constraints)
            $table->foreign('brand_id')->references('id')->on('brands_car')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("models_car", function (Blueprint $table) {
            //para dropar foreign nome da tabela + nome da coluna + foreign
            $table->dropForeign("models_car_brand_id_foreign");
        });
        Schema::dropIfExists('models_car');
    }
}
