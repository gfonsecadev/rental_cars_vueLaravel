<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(){
        //auth:api é igual ao middleware jwt_auth apelidado no Kernel.php
        $this->middleware('auth:api', ['except' => ['login']]);
    }


    public function login(Request $request){
        //recupero a solicitação enviada
        $credentials=$request->all(['name','password']);

        /*o auth utiliza o guard api que está com
          o driver JWT para fazer a emisão do token se
          o metodo attempt retornar um usuario com credenciais válidas*/
        $token=auth('api')->attempt($credentials);
        //o parâmetro de auth pode ser omitido pois em config/auth.php coloquei o guard default como api, deixei somente para fins didáticos

        dd($token);


    }

    public function logout(){
        auth('api')->logout();
        return response()->json(['msg'=>'Logout realizado com sucesso!']);
    }

    public function refresh(){
        $newToken=auth('api')->refresh();
        return response()->json(['newToken'=>$newToken]);
    }

    public function me(){
        //o parâmetro de auth pode ser omitido pois em config/auth.php coloquei o guard default como api, deixei somente para fins didáticos
        return response()->json(['user'=>auth('api')->user()]);
    }


}
