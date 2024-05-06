<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable=['model_car_id','plade','available','km'];
    use HasFactory;


    public function rules(){
        return [
            "model_car_id"=>"required|exists:models_car,id",
            "plade"=>"required|unique:cars,plade,$this->id|max:10",
            "available"=>"required|boolean",
            "km"=>"required|integer"
        ];
    }

    public function feedbacks(){
        return[
            "required"=>"Atributo :attribute é obrigatório!",
            "unique"=>"Atributo :attribute já existe!",
            "exists"=>"Atributo :attribute não existe na tabela de modelos!",
            "max"=>"Atributo :attribute não deve ser maior que 10!",
            "boolean"=>"Atributo :attribute deve ser um boleano 0 para não ou 1 para sim",
            "integer"=>"Atributo :attribute deve ser um número!"
        ];
    }

    //um carro pertence a um modelo
    public function model(){
        return $this->belongsTo(ModelCar::class,"model_car_id");
    }

    //um carro pode ser alugado por vários clientes
    public function clients(){
        return $this->belongsToMany(Client::class,"rentals");
    }
}
