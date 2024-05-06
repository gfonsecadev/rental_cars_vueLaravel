<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Repositories\RentalFilterRepositorie;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    public $rental;
    public function __construct(Rental $rental)
    {
        $this->rental=$rental;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clientFilter=new RentalFilterRepositorie($this->rental);
        if($request->has("search")){
            $clientFilter->filterSearch($request->search);
        }

       /*  if($request->has("relationship")){
            //coloquei o id do relacionamento no contexto do objeto para que o retorno não viesse nulo.
            $relationship="model:id,$request->relationship";
            $clientFilter->filterRelationship($relationship);
        } */

        if($request->has("filter")){
            $sql=explode("_",$request->filter);
            $clientFilter->filterSql($sql);
        }


        return $clientFilter->filterShow();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->rental->rules(),$this->rental->feedbacks());

        $this->rental->create($request->all());

        return "Locação criado para locação!";
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $clientShow=$this->rental->find($id);

        if($clientShow===null){
            return response()->json(['erro'=>'Rentale não existe na base de dados!'],404);
        }

        return $clientShow->with('cars')->get();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCarRequest  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $clientUpdated=$this->rental->find($id);

        if($clientUpdated===null){
            return response()->json(['Erro' => 'Carro não existe na base de dados'], 404);
        }

        //por mais que este modelo possui somente um campo para implementar deixei o metodo abaixo.
        if($request->method() == 'PATCH'){
            $rulesPatch = array();

            foreach($clientUpdated->rules() as $field => $rule){
                if (array_key_exists($field, $request->all())) {
                    $rulesPatch[$field] = $rule;
                }
            }

            $request->validate($rulesPatch,$clientUpdated->feedbacks());
        }else{
            $request->validate($clientUpdated->rules(),$clientUpdated->feedbacks());
        }

        $clientUpdated->fill($request->all());
        $clientUpdated->save();
        return "Locação atualizado na base de dados!";

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rental = $this->rental->find($id);

        if ($rental === null) {
            return response()->json(["Erro" => 'Locaçao não existe na base de dados!'], 404);
        }

        $rental->delete();
        return "Locação excluida da base de dados!";
    }
}
