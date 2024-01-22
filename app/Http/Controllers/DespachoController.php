<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Constants\ErrorCodes;
use App\Models\Pedido;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\isEmpty;

class DespachoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'archivo' => 'required|file|mimes:jpeg,png|max:2048',
                'jsonData' => 'required|json',
            ]);
        } catch (ValidationException $e) {
            return $this->setResponseErr($e, ErrorCodes::VALIDATION_ERROR);
        } catch (Throwable $e) {
            return $this->setResponseErr($e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $jsonData = json_decode($request->input('jsonData'), true);
        Log::info($jsonData);
        if ($request->hasFile('foto')) {
            $archivo = $request->file('foto');
            if (Storage::disk('public')->put('nombre_del_directorio/nombre_del_archivo', $archivo)) {
                $etiquetas = Pedido::whereIn('etiqueta', $jsonData)->get();
                if (!$etiquetas.isEmpty()) {
                    $filtrados = array_filter($etiquetas, function ($item) {
                        return $item->etapa === 'D';
                    });
                    if (!count($filtrados) == count($etiquetas)) {

                    }
                }
            }
            return response()->json(['mensaje' => 'Archivo recibido correctamente']);
        } else {
            return response()->json(['mensaje' => 'No se ha enviado ningún archivo'], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
