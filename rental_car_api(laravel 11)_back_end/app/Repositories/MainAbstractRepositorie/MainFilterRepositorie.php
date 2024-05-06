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
        //irei receber um array com 3 posições.
        $this->model=$this->model->where($sql[0],$sql[1],$sql[2]);
    }

    //retorna o objeto final das operações feitas acima
    public function filterShow(){
        return $this->model->get();
    }

}

