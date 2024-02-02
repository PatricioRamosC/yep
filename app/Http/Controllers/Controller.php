<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function setResponse($payload = null, $message = "OK", $error_code = 0, $statusCode = Response::HTTP_OK) {
        $responseData = [
            'error_code'    => $error_code,
            'message'       => $message
        ];
        Log::info(json_encode($responseData));
        if ($payload != null) {
            $responseData['payload'] = $payload;
        }
        return response()->json($responseData, $statusCode);
    }

    public function responseOK($payload = null, $errCode = Response::HTTP_OK) {
        return $this->setResponse($payload, "OK", 0, $errCode);
    }

    public function setResponseErr(Throwable $ex, $codeMessage, $errCode = Response::HTTP_INTERNAL_SERVER_ERROR) {
        Log::error("Controller : " . request()->route()->getAction('controller'));
        Log::error($codeMessage . " : " . $ex->getMessage());
        $responseData = [
            'error_code'    => $codeMessage,
            'message'       => trans('error-code.' . $codeMessage)
        ];
        return response()->json($responseData, $errCode);
    }

    public function setResponseErrBusiness($codeMessage, $errCode = Response::HTTP_INTERNAL_SERVER_ERROR) {
        $responseData = [
            'error_code'    => $codeMessage,
            'message'       => trans("error-code.$codeMessage")
        ];
        return response()->json($responseData, $errCode);
    }

    public function getToken(Request $request) {
        $authorizationHeader = $request->header('Authorization');
        // if ($authorizationHeader) {
        //     $parts = explode(' ', $authorizationHeader);
        //     if (count($parts) === 2) {
        //         return $parts[1];
        //     }
        // }
        return $authorizationHeader;
    }

}
