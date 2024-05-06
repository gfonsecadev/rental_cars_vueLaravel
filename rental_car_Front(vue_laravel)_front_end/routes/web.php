<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//todas rotas protegidas pelo token de autorizaçaõ jwt
Route::middleware("auth")->group(function(){
    //essa rota possui um controller
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    //essas rotas abaixo não possuem um controller(estão sendo chamadas diretamento na segunda função de callback do metodo em questão)
    Route::get('/brandCar', function(){
        return view("mainContent.brand_car");
    })->name('brand');
    Route::get('/modelCar', function(){
        return view(("mainContent.model_car"));
    })->name('model');
    Route::get('/car', function(){
        return view(("mainContent.car"));
    })->name('car');
    Route::get('/about', function(){
        return view(("mainContent.about"));
    })->name('about');
});


