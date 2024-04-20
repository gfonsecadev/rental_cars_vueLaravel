<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Repositories\CarFilterRepositorie;
use Illuminate\Http\Request;


class CarController extends Controller
{
    public $car;
    public function __construct(Car $car)
    {
        $this->car=$car;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $carFilter=new CarFilterRepositorie($this->car);
        if($request->has("search")){
            $search="model_car_id,$request->search";
            $carFilter->filterSearch($search);
        }

        if($request->has("relationship")){
            //coloquei o id do relacionamento no contexto do objeto para que o retorno não viesse nulo.
            $relationship="model:id,$request->relationship";
            $carFilter->filterRelationship($relationship);
        }

        if($request->has("filter")){
            $sql=explode("_",$request->filter);
            $carFilter->filterSql($sql);
        }


        return $carFilter->filterShow();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->car->rules(),$this->car->feedbacks());

        $this->car->create($request->all());

        return "Carro criado para locação!";
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $carShow=$this->car->find($id);

        if($carShow===null){
            return response()->json(['erro'=>'Carro não existe na base de dados!'],404);
        }

        return $carShow->with('model')->get();
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
        $carUpdated=$this->car->find($id);

        if($carUpdated===null){
            return response()->json(['Erro' => 'Carro não existe na base de dados'], 404);
        }

        if($request->method() == 'PATCH'){
            $rulesPatch = array();

            foreach($carUpdated->rules() as $field => $rule){
                if (array_key_exists($field, $request->all())) {
                    $rulesPatch[$field] = $rule;
                }
            }

            $request->validate($rulesPatch,$carUpdated->feedbacks());
        }else{
            $request->validate($carUpdated->rules(),$carUpdated->feedbacks());
        }

        $carUpdated->fill($request->all());
        $carUpdated->save();
        return "Carro atualizado na base de dados!";

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $car = $this->car->find($id);
        //condição se não encontrar uma marca para exclusão
        if ($car === null) {
            return response()->json(["Erro" => 'Carro não existe na base de dados!'], 404);
        }

        $car->delete();
        return "Carro excluída da base de dados!";
    }
}
