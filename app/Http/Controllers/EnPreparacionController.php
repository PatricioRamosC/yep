<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Throwable;
use Illuminate\Support\Facades\Log;
use App\Constants\ErrorCodes;
use App\Models\GrupoSku;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;

class EnPreparacionController extends Controller
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
    public function show(int $grupo)
    {
        try {
            Log::info("Listando grupos con estadp [$grupo]");
            $grupoSku = GrupoSku::where('id_grupo', $grupo)->get();
            return $this->responseOK($grupoSku);
        } catch (ModelNotFoundException $e) {
            return $this->setResponseErr($e, Response::HTTP_NO_CONTENT);
        } catch (Throwable $e) {
            return $this->setResponseErr($e, ErrorCodes::SHOW_ERROR);
        }
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
