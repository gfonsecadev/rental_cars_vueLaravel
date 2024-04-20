<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//terei que renomear a tabela para o eloquent fazer o match com a do BD que é brands_car
//Isso é preciso pois o eloquent somente adiciona um s no final da classe e ficaria brand_cars e o certo é brands_car
class BrandCar extends Model
{
    protected $table="brands_car";

    use HasFactory;
    protected $fillable=['name', 'image'];

    //estes dois métodos abaixo serão inseridos no validate do controller para maior organização e menor repetição
    //retornará a regras para o validate no controller
    public function rules(){
        return [
            //o unique recebe por padrao a tabela, a coluna e o que deve desconsiderar
            //nos será útil na atualização pois o dados já existe
            //id estará no contexto do objeto instanciado na ação do controller
            'name'=>"required|unique:brands_car,name,$this->id|min:3",
            'image'=>'required|file|mimes:png'
            //atributo image deverá ser um arquivo e do tipo png
        ];
    }
    //retornará os feedbacks para o validate no controller
    public function feedbacks(){
        return [
            'required'=>'Atributo :attribute é obrigatório!',
            'unique'=>'Atributo :attribute já existe!',
            'min'=>'Atributo :attribute exije no minimo 3 letras!',
            'file'=>'Atributo :attribute deve ser um arquivo',
            'mimes'=>'Atributo :attribute deve ser do tipo .png'
        ];
    }

    //uma marca pode ter vários modelos
    public function models(){
        //como o nome da foreign não seguiu o padrão do eloquent que é nome tabela relacionada(brand_car) + _id
        //foi preciso passar o segundo parametro que é o nome da foreign
        return $this->hasMany(ModelCar::class,"brand_id");
    }

}
