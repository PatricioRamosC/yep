<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\OrderGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Constants\Constants;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use App\Constants\ErrorCodes;
use App\Models\Courier;

class CourierController extends Controller
{
    public function show() {
        try {
            Log::info("Listando grupos en estado 'Despachado'.");
            $courier = Courier::all();
            Log::info('Registros encontrados ' . $courier->count());
            return $this->responseOK($courier);
        } catch (ModelNotFoundException $e) {
            return $this->setResponseErr($e, Response::HTTP_NO_CONTENT);
        } catch (Throwable $e) {
            return $this->setResponseErr($e, ErrorCodes::SHOW_ERROR);
        }
    }
}
