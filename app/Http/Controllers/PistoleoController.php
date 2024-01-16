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
            Log::info($request->only(['id_usuario', 'etiqueta', 'barcode', 'quantity', 'etapa']));
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
            Log::info($etiqueta);
            $detalle = Pistoleo::where('id_usuario', $etiqueta->id_usuario)->get();
            return $this->responseOK($detalle, Response::HTTP_CREATED);
        } catch(Throwable $e) {
            return $this->setResponseErr($e, ErrorCodes::CREATE_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($etiqueta)
    {
        try {
            $detalle = Pistoleo::where('etiqueta', $etiqueta)->get();
            $region = Pistoleo::findOrFail($detalle);
            return $this->responseOK($region);
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
            $region = Pistoleo::findOrFail($id);
            $region->update($request->all());
            return $this->responseOK($region);
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
            $region = Pistoleo::findOrFail($id);
            $region->delete();
            return $this->responseOK($region);
        } catch (ModelNotFoundException $e) {
            return $this->setResponseErr($e, Response::HTTP_NO_CONTENT);
        } catch(Throwable $e) {
            return $this->setResponseErr($e, ErrorCodes::VALIDATION_ERROR);
        }
    }
}
