<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelCar extends Model
{
    //preciso pois o eloquent procura somente adicionando um s no final da classe
    //ele iria procurar por model_cars no banco e o certo é models_car
    protected $table="models_car";
    use HasFactory;
    protected $fillable=['brand_id','name','image','number_doors','seats','air_bag','abs'];

    //regras de validação
    public function rules(){
        return [
            'brand_id'=>'required|exists:brands_car,id',
            //unique=precisamos passar o 3° parâmetro pois se for atualizaçaõ o nome já existirá
            //o 3°parâmetro é o id a ser desconsiderado
            'name'=>"required|min:3|unique:models_car,name,$this->id",
            'image'=>'required|file|mimes:png',
            'number_doors'=>'required|integer|max:5',
            'seats'=>'required|integer|max:20',
            'air_bag'=>'required|boolean',//deve ser um boleano true, false, 1, 0, "1", "0"
            'abs'=>'required|boolean',
        ];
    }
    //mensagens de erro de validação
    public function feedbacks(){
        return [
        'required'=>'Atributo :attribute é obrigatório',
        'min'=>'Atributo :attribute deve conter no mínimo 3 letras',
        'exists'=>'Atributo :attribute não existe na tabela de Marcas!',
        'unique'=>'Atributo :attribute já existe!',
        'file'=>'Atributo :attribute deve ser um arquivo',
        'mimes'=>'Atributo :attribute deve ser um arquivo do tipo png',
        'integer'=>'Atributo :attribute deve ser um número',
        'number_doors.max'=>'Atributo :attribute deve ter no máximo 5 portas',
        'seats.max'=>'Atributo :attribute deve ter no máximo 20 lugares',
        'boolean'=>'Atributo :attribute dever ser um boleano(true, false ou 1, 0'
        ];
    }

    //um modelo de carro pertence a uma marca.
    public function brand(){
        return $this->belongsTo(BrandCar::class);
    }
}
