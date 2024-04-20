<?php

namespace App\Http\Controllers;

use App\Models\BrandCar;
use App\Repositories\BrandFilterRepositorie;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

//controlador de marca de carro
class BrandCarController extends Controller
{   //essa variavel receberá a instância do model marca
    public $brandCar;
    public function __construct(BrandCar $brandCar)
    {
        $this->brandCar = $brandCar;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //exibição de todas marcas de carro
    public function index(Request $request)
    {
        //instância de uma classe com os filtros
        $brandFilter=new BrandFilterRepositorie($this->brandCar);

        //filtro do objeto com selectRaw na classe
        if($request->has("search")){
            $brandFilter->filterSearch($request->search);
        }

        //filtro do relacionamento com with na classe
        if($request->has("relationship")){
            //para o with retornar corretamente a foreign(brand_id) deve estar no contexto
            $relationship="models:brand_id,$request->relationship";
            $brandFilter->filterRelationship($relationship);
        }

        //filtro sql com operador where na classe
        if($request->has("filter")){
            //utilizo o explode para dividir a string em uma posição de um array a cada underline(_)
            $sql=explode("_",$request->filter);
            $brandFilter->filterSql($sql);
        }


        //retorna o objeto final com o fitro sequenciado acima.
        return $brandFilter->filterShow();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    //esse método não é preciso em api pois são responsaveis de renderizar a view para o store
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
    //criação de marca de carros
    public function store(Request $request)
    {   //validação do request com as regras e feedbacks no molelo
        $request->validate($this->brandCar->rules(), $this->brandCar->feedbacks());

        //persistir arquivos em disco da aplicação no laravel
        //o primeiro parâmetro é o nome da pasta e o segundo o disco.
        //o retorno é o caminho do armazenamento.
        $pathImageSaved=$request->file("image")->store("brand_logos","public");

        $brandCar = $this->brandCar->create([
            "name"=>$request->get("name"),
            "image"=>$pathImageSaved
        ]);

        return "Modelo de carro criado na base de dados";
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer// aqui mudamos para o tipo de parametro que será recebido
     * @return \Illuminate\Http\Response
     */
    //exibição de somente uma marca de carro.
    public function show($id)
    {
        $brandCar = $this->brandCar->find($id);

        //condição se não encontrar uma marca para exibição
        if ($brandCar === null) {
            //recebe como parâmetro a mensagem e o status http
            return response()->json(["erro" => "Marca de carro não existe na base de dados!"], 404);
        }
        return $brandCar->with("models")->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BrandCar  $brandCar
     * @return \Illuminate\Http\Response
     */
    //esse método não é preciso em api pois são responsaveis de renderizar a view para o update
    public function edit(BrandCar $brandCar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer \\aqui passamos o tipo de dados que queremos
     * @return \Illuminate\Http\Response
     */
    //atualização de uma marca de carro
    public function update(Request $request, $id)
    {
        $brandCar = $this->brandCar->find($id);
        //condição se não encontrar uma marca para edição
        if ($brandCar === null) {
            return response()->json(['Erro' => 'Marca de carro não existe na base de dados'], 404);
        }

        //metodo patch para atualização de partes do objeto
        if ($request->method() == "PATCH") {
            $rulesPatch = array();
            //percorremos o array de regras do model para conferir se o request contém algum campo da regra
            foreach ($brandCar->rules() as $field => $rule) {
                //se o request contiver o campo da regra do model
                if (array_key_exists($field, $request->all())) {
                    $rulesPatch[$field] = $rule;
                }
            }//validação do request com as regras e feedbacks no model
            //validate para o metodo patch de acordo com o retorno acima
            $request->validate($rulesPatch,$brandCar->feedbacks());
        } else {
            //validação do request com as regras e feedbacks no molelo
            //validação normal para put
            $request->validate($brandCar->rules(), $brandCar->feedbacks());
        }

        //esses ifs abaixo nos ajuda a trabalhar tanto com put e patch

        //condição se o request contiver a imagem
        //deletamos o caminho antigo utilizando a instãncia que contém todo corpo do objeto procurado pelo find
        if($request->file("image")){
            Storage::disk("public")->delete($brandCar->image);
            //retorna o caminho do armazenamento
            $pathImageUpdated=$request->file("image")->store("brand_logos","public");
            //sobreescrevo o caminho antigo da imagem pelo novo
            $brandCar->image=$pathImageUpdated;
        }

        //se houver o nome
        if($request->get("name")){
            $brandCar->name=$request->get("name");
        }

        $brandCar->save();

        return "Marca de carro atualizada na base de dados!";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    //deleta uma marca de carro
    public function destroy($id)
    {
        $brandCar = $this->brandCar->find($id);
        //condição se não encontrar uma marca para exclusão
        if ($brandCar === null) {
            return response()->json(["Erro" => 'Marca de carro não existe na base de dados!'], 404);
        }

        Storage::disk("public")->delete($brandCar->image);
        $brandCar->delete();
        return "Marca de carro excluída da base de dados!";
    }
}
