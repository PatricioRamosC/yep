<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\MenuItem;
use Symfony\Component\HttpFoundation\Response;
use App\Constants\ErrorCodes;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MenuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $regiones = MenuItem::all();
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
                'text' => 'required',
                'viewName' => 'required|unique:menu_items',
                'securityLevel' => 'required',
                'icon' => 'required',
            ]);
        } catch(Throwable $e) {
            return $this->setResponseErr($e, ErrorCodes::VALIDATION_ERROR);
        }
        try {
            $region = MenuItem::create($request->all());
            return $this->responseOK($region, Response::HTTP_CREATED);
        } catch(Throwable $e) {
            return $this->setResponseErr($e, ErrorCodes::CREATE_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $region = MenuItem::findOrFail($id);
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
                'text' => 'required',
                'viewName' => 'required|unique:menu_items',
                'securityLevel' => 'required',
                'icon' => 'required',
                // Agrega aquí otras validaciones según tus campos
            ]);
        } catch(Throwable $e) {
            return $this->setResponseErr($e, ErrorCodes::VALIDATION_ERROR);
        }
        try {
            $region = MenuItem::findOrFail($id);
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
            $region = MenuItem::findOrFail($id);
            $region->delete();
            return $this->responseOK($region);
        } catch (ModelNotFoundException $e) {
            return $this->setResponseErr($e, Response::HTTP_NO_CONTENT);
        } catch(Throwable $e) {
            return $this->setResponseErr($e, ErrorCodes::VALIDATION_ERROR);
        }
    }
}
