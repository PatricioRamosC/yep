<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function report(Throwable $exception)
    {
        if ($exception instanceof QueryException) {
            // Registrar la excepción de base de datos
            Log::error($exception->getMessage());
        }
        parent::report($exception);
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof QueryException) {
            // Responder con un mensaje amigable al usuario
            return response()->json(['message' => 'Ocurrió un error en la base de datos.'], 500);
        }
        // if ($exception instanceof ValidationException) {
        //     return response()->json([
        //         'error' => [
        //             'message' => 'Validation failed',
        //             'errors' => $exception->errors(),
        //         ]
        //     ], Response::HTTP_UNPROCESSABLE_ENTITY);
        // }
        Log::error('Exception ' . $exception->getMessage());
        Log::error('Trace ' . $exception->getTraceAsString());
        return response()->json([
                'message' => 'Falla en la aplicación, reporte a soporte técnico.',
            ], Response::HTTP_EXPECTATION_FAILED);

        return parent::render($request, $exception);
    }

}
