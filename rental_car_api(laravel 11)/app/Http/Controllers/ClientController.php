<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Repositories\ClientFilterRepositorie;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public $client;
    public function __construct(Client $client)
    {
        $this->client=$client;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clientFilter=new ClientFilterRepositorie($this->client);
        if($request->has("search")){
            $clientFilter->filterSearch($request->search);
        }

        if($request->has("relationship")){
            //coloquei o id do relacionamento no contexto do objeto para que o retorno não viesse nulo.
            $relationship="cars:id,$request->relationship";
            $clientFilter->filterRelationship($relationship);
        }

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
        $request->validate($this->client->rules(),$this->client->feedbacks());

        $this->client->create($request->all());

        return "Cliente criado para locação!";
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $clientShow=$this->client->find($id);

        if($clientShow===null){
            return response()->json(['erro'=>'Cliente não existe na base de dados!'],404);
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
        $clientUpdated=$this->client->find($id);

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
        return "Cliente atualizado na base de dados!";

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = $this->client->find($id);

        if ($client === null) {
            return response()->json(["Erro" => 'Carro não existe na base de dados!'], 404);
        }

        $client->delete();
        return "Cliente excluido da base de dados!";
    }
}
