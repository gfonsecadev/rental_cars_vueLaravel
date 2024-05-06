<?php

namespace App\Http\Controllers;

use App\Models\ModelCar;
use App\Repositories\ModelFilterRepositorie;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModelCarController extends Controller
{
    public $modelCar;
    public function __construct(ModelCar $modelCar) {
        $this->modelCar=$modelCar;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //metodo para procura de todos os dados.
    public function index(Request $request)
    {   //instância de uma classe com os filtros
        $modelFilter=new ModelFilterRepositorie($this->modelCar);

        //filtro para o modelo implementado.
        //has verifica se há uma correspondência do tipo informado
        if($request->has("search")){
            $search="brand_id,$request->search";
            $modelFilter->filterSearch($search);
        }
        //filtro para o relacionamento do modelo implementado
        if($request->has("relationship")){
          $relationship="brand:id,$request->relationship";
          $modelFilter->filterRelationship($relationship);
        }

        //filtro sql
        if($request->has("filter")){
            $sql=explode("_",$request->filter);
            $modelFilter->filterSql($sql);
        }

        //retorna o objeto final com o fitro sequenciado acima.
        return $modelFilter->filterShow();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    //no contexto de api não é preciso
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validação com regras e feedback implementadas no modelo
        $request->validate($this->modelCar->rules(), $this->modelCar->feedbacks());
        $newModelCar=$this->modelCar->fill($request->all());
        $newModelCar->image=$request->file("image")->store("model_logos","public");
        $newModelCar->save();
        return "Modelo de carro da marca criado na base de dados";
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    //exibe um único dado
    public function show($id)
    {
        $modelSearched=$this->modelCar->find($id);

        if($modelSearched===null){
            return response()->json(['erro'=>'Modelo de carro não existe na base de dados!'],404);
        }

        return $modelSearched->with("brand")->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\M  $m
     * @return \Illuminate\Http\Response
     */
    //não é preciso  no contexto de api
    public function edit(ModelCar $model_car)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $modelCarUpdated=$this->modelCar->find($id);

        if($modelCarUpdated===null){
            return response()->json(["erro"=>"Modelo de carro não existe na base de dados!"],404);
        }

        //patch não exige todos os campos para atualização
        if($request->method()=="PATCH"){
            $rulesPatch=array();
            //mais detalhes olhar no controller BrandCar
            foreach($modelCarUpdated->rules() as $field => $rule){
                if(array_key_exists($field, $request->all())){
                    $rulesPatch[$field]=$rule;
                }

            }

            //array criado acima conterá as regras que forem enviadas no request(claro se contiverem os campos aceitos no modelo)
            $request->validate($rulesPatch, $modelCarUpdated->feedbacks());
        }else{
            //se for metodo put é obrigatório todos os campos
            $request->validate($modelCarUpdated->rules(), $modelCarUpdated->feedbacks());
        }

        if($request->file("image")){
            //exclusão do arquivo anterior
            Storage::disk("public")->delete($modelCarUpdated->image);
        }

        //sobreescreve independente de ser patch ou put
        $modelCarUpdated->fill($request->all());
        //se imagem form passada um novo caminho é passado se não mantém o antigo.
        $modelCarUpdated->image=$request->file("image") ? $request->file("image")->store("model_logos","public") : $modelCarUpdated->image;
        $modelCarUpdated->save();

        return "Modelo de carro atualizado na base de dados";

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modelCarDeleted=$this->modelCar->find($id);

        if($modelCarDeleted===null){
            return response()->json(['erro'=>'Modelo não existe na base de dados!'],404);
        }

        Storage::disk("public")->delete($modelCarDeleted->image);
        $modelCarDeleted->delete();

        return "Modelo de carro excluída da base de dados!";
    }
}
