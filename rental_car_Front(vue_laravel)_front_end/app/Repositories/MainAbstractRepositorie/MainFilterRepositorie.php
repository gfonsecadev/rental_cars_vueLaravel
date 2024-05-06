<?php
namespace App\Repositories\MainAbstractRepositorie;

use Illuminate\Database\Eloquent\Model;

abstract class MainFilterRepositorie{

    public $model;
    public function __construct(Model $model)
    {
        $this->model=$model;
    }

    //filtro para recuperar campos do modelo implementado (enviados pela query)
    public function filterSearch($field){
        //selectRaw interpreta a query identiticando cada virgula como um campo
        $this->model=$this->model->selectRaw($field);
    }

    //filtro para recuperar campos  do relacionamento do modelo implementado (enviados pela query)
    public function filterRelationship($relationship){
        $this->model=$this->model->with($relationship);

    }

    //filtro com operadores sql where
    public function filterSql($sql){
        //irei receber um array com uma ou multiplas consultas que ainda estação entre _
        //percorremos o array recebido e a cada posição que é uma consulta separamos os campos com explode
        foreach($sql as $filters){
            $filter=explode("*",$filters);
            //a cada laço uma consulta será aplicada ao objeto principal
            $this->model=$this->model->where($filter[0],$filter[1],$filter[2]);

        }



    }
    //mostra todos os registros sem paginação com o relacionamento
    public function allWhitoutPagination(){
        return $this->model->with('brand')->get();
    }

    //retorna o objeto final das operações feitas acima
    public function filterShow($perPage){
        return $this->model->paginate($perPage);

    }

}

