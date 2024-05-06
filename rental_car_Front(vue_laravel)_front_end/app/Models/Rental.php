<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $fillable = ['client_id', 'car_id', 'rental_start_date', 'rental_end_date', 'car_delivered_date', 'rent_value', 'start_km', 'end_km'];
    use HasFactory;

    public function rules(){
        return [
            "client_id"=>"required|exists:clients,id",
            "car_id"=>"required|exists:cars,id",
            "rental_start_date"=>"required|date",
            "rental_end_date"=>"required|date",
            "car_delivered_date"=>"required|date",
            "rent_value"=>"required|numeric",
            "start_km"=>"required|integer",
            "end_km"=>"required|integer"
        ];
    }

    public function feedbacks(){
        return [
            "required"=>"Atributo :attribute é obrigatório!",
            "car_id.exists"=>"Atributo :attribute não existe na tabela de carros!",
            "date"=>"Atributo :attribute não é uma data válida!",
            "client_id.exists"=>"Atributo :attribute não existe na tabela de clientes!",
            "integer"=>"Atributo :attribute deve ser um número!"
        ];
    }
}

