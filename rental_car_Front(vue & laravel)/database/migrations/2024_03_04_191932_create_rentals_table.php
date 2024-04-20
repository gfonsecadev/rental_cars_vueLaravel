<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   //tabela de locações feitas esta é a tabela auxiliar para o relacionamento de N:N entre clientes e carros
        //um cliente pode alugar vários carros e um carro pode ser alugado por varios clientes(com agendamentos diferentes é claro)

        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            //chave do cliente
            $table->unsignedBigInteger('client_id');
            //chave do carro
            $table->unsignedBigInteger('car_id');
            //data inicio aluquel
            $table->dateTime('rental_start_date');
            //data fim aluquel
            $table->dateTime('rental_end_date');
            //data carro entregue
            $table->dateTime('car_delivered_date');
            //valor aluguel
            $table->float('rent_value', 8, 2);
            $table->integer('start_km');
            $table->integer('end_km');
            $table->timestamps();

            //foreign key (constraints)
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('car_id')->references('id')->on('cars');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rentals', function (Blueprint $table) {
            //para dropar foreign nome da tabela + nome da coluna + foreign
            $table->dropForeign("rental_client_id_foreign");
            $table->dropForeign("rental_car_id_foreign");
        });


        Schema::dropIfExists('rentals');
    }
}
