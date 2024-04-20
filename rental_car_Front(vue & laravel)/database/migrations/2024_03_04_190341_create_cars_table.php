<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //tabela de carros
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            //relacionamento com modelos de carro: um modelo posssui varios carros
            $table->unsignedBigInteger('model_car_id');
            $table->string('plade', 10)->unique();//placa
            $table->boolean('available');//disponibilidade do carro
            $table->integer('km');
            $table->timestamps();

            //foreign key (constraints)
            $table->foreign('model_car_id')->references('id')->on('models_car');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cars',function(Blueprint $table){
            //para dropar foreign nome da tabela + nome da coluna + foreign
            $table->dropForeign('cars_model_car_id_foreign');
        });
        Schema::dropIfExists('cars');
    }
}
