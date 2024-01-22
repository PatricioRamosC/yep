<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Pistoleo;
use Symfony\Component\HttpFoundation\Response;
use App\Constants\ErrorCodes;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PistoleoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $regiones = Pistoleo::all();
            return $this->responseOK($regiones);
        } catch(Throwable $e) {
            return $this->setResponseErr($e, ErrorCodes::LIST_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_usuario'    => 'required|numeric',
                'etiqueta'      => 'required',
                'barcode'       => 'required',
                'quantity'      => 'required|numeric',
                'etapa'         => 'required',
            ]);
        } catch(Throwable $e) {
            return $this->setResponseErr($e,
                Response::HTTP_BAD_REQUEST
                // ErrorCodes::VALIDATION_ERROR
            );
        }
        try {
            $etiqueta = Pistoleo::create(
                $request->only(['id_usuario', 'etiqueta', 'barcode', 'quantity', 'etapa'])
            );
            Log::info("Registro creado.");
            return $this->show($etiqueta->id_usuario, $etiqueta->etapa);
        } catch(Throwable $e) {
            return $this->setResponseErr($e, ErrorCodes::CREATE_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($userId, $etapa)
    {
        try {
            $detalle = Pistoleo::where('id_usuario', $userId)
                ->where('etapa', $etapa)
                ->get();
            Log::info("Retornando registros. [" . $userId . "] [" . $etapa . "]");
            return $this->responseOK($detalle);
        } catch (ModelNotFoundException $e) {
            return $this->setResponseErr($e, Response::HTTP_NO_CONTENT);
        } catch (Throwable $e) {
            return $this->setResponseErr($e, ErrorCodes::SHOW_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        try {
            $request->validate([
                'etiqueta'      => 'required',
                'barcode'       => 'required',
                'quantity'      => 'required',
                'etapa'         => 'required',
            ]);
        } catch(Throwable $e) {
            return $this->setResponseErr($e, ErrorCodes::VALIDATION_ERROR);
        }
        try {
            $pistoleo = Pistoleo::findOrFail($id);
            $pistoleo->update($request->all());
            Log::info("Actualizando registro.");
            return $this->show($pistoleo->id_usuario, $pistoleo->etapa);
        } catch (Throwable $e) {
            return $this->setResponseErr($e, ErrorCodes::UPDATE_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $pistoleo = Pistoleo::findOrFail($id);
            $pistoleo->delete();
            Log::info("Borrando registro.");
            return $this->show($pistoleo->id_usuario, $pistoleo->etapa);
        } catch (ModelNotFoundException $e) {
            return $this->setResponseErr($e, Response::HTTP_NO_CONTENT);
        } catch(Throwable $e) {
            return $this->setResponseErr($e, ErrorCodes::VALIDATION_ERROR);
        }
    }
}
