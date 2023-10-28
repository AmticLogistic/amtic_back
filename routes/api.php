<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\UtilsController;
use App\Http\Controllers\ProcessController;

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
Route::group(['prefix' => 'auth'], function () {
    Route::post('signIn',[ LoginController::class,'signIn' ]);
    Route::post('changePassword',[ LoginController::class,'changePassword' ]);
});


Route::group(['middleware' => 'jwt.auth'], function () {
    
});

Route::group(['prefix' => 'almacen'], function () {
    
    Route::get('listCategorias',[ UtilsController::class,'listCategorias' ]);
    Route::post('saveCategorias',[ UtilsController::class,'saveCategorias' ]);
    Route::post('editCategorias',[ UtilsController::class,'editCategorias' ]);

    Route::get('listMarcas',[ UtilsController::class,'listMarcas' ]);
    Route::post('saveMarcas',[ UtilsController::class,'saveMarcas' ]);
    Route::post('editMarcas',[ UtilsController::class,'editMarcas' ]);

    Route::get('getPersons',[ UtilsController::class,'getPersons' ]);
    

    Route::get('listMateriales',[ UtilsController::class,'listMateriales' ]);
    Route::get('utils',[ UtilsController::class,'utils' ]);
    Route::post('saveMaterial',[ UtilsController::class,'saveMaterial' ]);
    Route::get('listServicios',[ UtilsController::class,'listServicios' ]);
});

Route::group(['prefix' => 'utils'], function () {
    Route::get('getPersons',[ UtilsController::class,'getPersons' ]);
    Route::get('getProviders',[ UtilsController::class,'getProviders' ]);
    Route::get('getTransport',[ UtilsController::class,'getTransport' ]);
    Route::get('getOrderOne/{id}',[ UtilsController::class,'getOrderOne' ]);
});

Route::group(['prefix' => 'process'], function () {
    Route::post('saveRequerimiento',[ ProcessController::class,'saveRequerimiento' ]);
    Route::get('listRequerimientos',[ ProcessController::class,'listRequerimientos' ]);
    Route::post('saveOrden',[ ProcessController::class,'saveOrden' ]);
    Route::get('listOrden',[ ProcessController::class,'listOrden' ]);

    Route::post('saveInput',[ ProcessController::class,'saveInput' ]);
    Route::get('obtenerEntradas',[ ProcessController::class,'obtenerEntradas' ]);
    
});