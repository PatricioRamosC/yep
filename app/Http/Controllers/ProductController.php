<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Product;
use App\Constants\ErrorCodes;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $filtro)
    {
        try {
            Log::info("Listando skus [$filtro]");
            $filtro2 = strtoupper("$filtro%");
            $filtro3 = strtoupper("%$filtro%");
            $skus = Product::where('Description', 'ilike', strtoupper($filtro))
                    ->union(Product::where('Description', 'ilike', $filtro2))
                    ->union(Product::where('Description', 'ilike', $filtro3))
                    ->get();
            Log::info('Registros encontrados ' . $skus->count());
            return $this->responseOK($skus);
        } catch (ModelNotFoundException $e) {
            return $this->setResponseErr($e, Response::HTTP_NO_CONTENT);
        } catch (Throwable $e) {
            return $this->setResponseErr($e, ErrorCodes::SHOW_ERROR);
        }
    }

    /**
     * Consulta de Producto por el código de barra del SKU.
     */
    public function consultaBarcode(String $barcode) {
        try {
            Log::info("Listando consultando por barcode [$barcode]");
            $sku = Product::where('Code_bar', $barcode)
                    ->first();
            return $this->responseOK($sku);
        } catch (ModelNotFoundException $e) {
            return $this->setResponseErr($e, Response::HTTP_NO_CONTENT);
        } catch (Throwable $e) {
            return $this->setResponseErr($e, ErrorCodes::SHOW_ERROR);
        }
    }
}
