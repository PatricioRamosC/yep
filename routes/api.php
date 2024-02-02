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
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\EnPreparacionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CourierController;

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
    Route::get('/etiquetas/grupos-despacho', [EtiquetaController::class, 'listarGruposDespachado']);
    Route::post('/etiquetas', [EtiquetaController::class, 'store']);
    // Route::put('/etiquetas/{id}', [EtiquetaController::class, 'update']);
    Route::delete('/etiquetas/{id}', [EtiquetaController::class, 'destroy']);
    Route::get('/etiquetas/traking-codes/{id}', [EtiquetaController::class, 'listarEtiquetas']);
    Route::get('/etiquetas/validar-etiqueta/{grupoId}/{barcode}', [EtiquetaController::class, 'validarEtiqueta']);
    Route::get('/etiquetas/{userId}/{etapa}', [EtiquetaController::class, 'show']);
    Route::put('/etiquetas/despachar', [EtiquetaController::class, 'despacharEtiqueta']);
    Route::put('/etiquetas/entregar', [EtiquetaController::class, 'entregarEtiqueta']);

    Route::post('/despacho', [DespachoController::class, 'store']);

    Route::get('/grupo/despachar', [GrupoController::class, 'listarDespachar']);
    Route::get('/grupo/grupos-despacho', [GrupoController::class, 'listarGruposDespachado']);
    Route::get('/grupo/{etapa}', [GrupoController::class, 'show']);
    Route::get('/grupo/skus/{id}', [GrupoController::class, 'skus']);
    Route::post('/grupo', [GrupoController::class, 'store']);

    Route::get('/en-preparacion/{grupo}', [EnPreparacionController::class, 'show']);

    Route::get('/producto/{filtro}', [ProductController::class, 'show']);
    Route::get('/producto/consulta-barcode/{barcode}', [ProductController::class, 'consultaBarcode']);

    Route::get('/courier', [CourierController::class, 'show']);

});
