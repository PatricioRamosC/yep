<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ValidateLoginToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::debug('middleware - ValidateLoginToken');
        $url = config('services.apis.login_manager_url');
        $token = $request->header('Authorization');
        $response = Http::withHeaders([
            'Authorization' => $token,
            'Content-Type'  => 'application/json',
            ])
            ->post($url);

            // Verificar la respuesta del sistema de Login
        if ($response->status() == 200) {
            Log::debug("Token valido.");
            return $next($request);
        } else {
            Log::debug("Token invalido [$token]");
            return $this->setResponseErrBusiness('invalid-token');
        }
    }

    public function setResponseErrBusiness($errCode) {
        $responseData = [
            'error_code'    => $errCode,
            'message'       => trans('error-code.' . $errCode)
        ];
        return response()->json($responseData, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
