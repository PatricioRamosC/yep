<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\PistoleoController;
use App\Http\Controllers\EtiquetaController;
use App\Http\Controllers\DespachoController;

Route::group(['prefix' => 'v1/', 'middleware' => 'tokenAutentication'], function () {
    // Rutas CRUD para MenuItems
    Route::resource('/menu-items', MenuItemController::class);

    // Rutas para el Pistoleo
    Route::get('/pistoleos', [PistoleoController::class, 'index']);
    Route::post('/pistoleos', [PistoleoController::class, 'store']);
    Route::get('/pistoleos/{userId}/{etiqueta}', [PistoleoController::class, 'show']);
    Route::put('/pistoleos/{id}', [PistoleoController::class, 'update']);
    Route::delete('/pistoleos/{id}', [PistoleoController::class, 'destroy']);

    Route::get('/etiquetas', [EtiquetaController::class, 'index']);
    Route::post('/etiquetas', [EtiquetaController::class, 'store']);
    Route::get('/etiquetas/{userId}/{etapa}', [EtiquetaController::class, 'show']);
    Route::put('/etiquetas/{id}', [EtiquetaController::class, 'update']);
    Route::delete('/etiquetas/{id}', [EtiquetaController::class, 'destroy']);

    Route::post('/despacho', [DespachoController::class, 'store']);

});
