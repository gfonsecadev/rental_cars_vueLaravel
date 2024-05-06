<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable=['name','cpf'];

    public function rules(){
        return [
            "name"=>"required",
            "cpf"=>"required|digits:11|integer|unique:clients,cpf,$this->id"
        ];
    }

    public function feedbacks(){
        return [
            "required"=>"Attributo :attribute é obrigatório!",
            "unique"=>"Atributo :attribute já existe!",
            "digits"=>"Atributo :attribute deve conter 11 digitos",
            "integer"=>"Atributo :attribute de ser um número"
        ];
    }

    //um cliente pode alugar vários carros
    public function cars(){
        return $this->belongsToMany(Car::class,"rentals");
    }
}
