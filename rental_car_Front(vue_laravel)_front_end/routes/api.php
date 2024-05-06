<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


    //Todas essas rotas estão com um middleware no construtor da classe com excessão de login
    Route::post('login','App\Http\Controllers\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('me', 'App\Http\Controllers\AuthController@me');



//Essa será a raiz da api(api/v1/...)tudo daqui para frente passará pela autorizaçaõ do token JWT através do middleware apelidado por mim por jwt_auth que foi configurado em app/Http/Kernel.php ou auth:api como está na documentação
Route::prefix('v1')->middleware( "auth:api")->group(function () {
    //quando utilizamos resource todos os metodos para todos os verbos são usados.
    //Route::Resource("client","App\Http\Controllers\ClientController");
    //quando utilizamos apiResource os metodos create e edit não são usados pois esses são utilizados para renderizar views para criação e edição, e o que precisamos é o store e update
    Route::apiResource("brand_car", "App\Http\Controllers\BrandCarController");
    Route::apiResource("model_car", "App\Http\Controllers\ModelCarController");
    Route::apiResource("car", "App\Http\Controllers\CarController");
    Route::apiResource("rental", "App\Http\Controllers\RentalController");
    Route::apiResource("client", "App\Http\Controllers\ClientController");
});
