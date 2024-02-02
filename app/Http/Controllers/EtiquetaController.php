<?php

namespace App\Http\Controllers;

use App\Constants\Constants;
use Throwable;
use App\Models\GrupoPedido;
use App\Models\Pedido;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Constants\ErrorCodes;
use Brick\Math\BigInteger;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EtiquetaController extends Controller
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
        Log::info("Creando pedidos.");
        try {
            $request->validate([
                'id_usuario'    => 'required|numeric',
                'etiqueta'      => 'required',
                'barcode'       => 'required',
                'cantidad'      => 'required|numeric',
                'etapa'         => 'required',
            ]);
        } catch(Throwable $e) {
            Log::error("Falla en la validación.");
            return $this->setResponseErr($e,
                Response::HTTP_BAD_REQUEST
                // ErrorCodes::VALIDATION_ERROR
            );
        }

        // Flujo de etiqueta encontrada
        try {
            Log::info("Buscando etiqueta {$request['etiqueta']}...");
            $pedido = Pedido::where('etiqueta', $request['etiqueta'])->first();
            if ($pedido != null) {
                Log::info("Etiqueta {$request['etiqueta']} encontrada");
                $grupo = GrupoPedido::findOrFail($pedido->id_grupo);
                Log::info("Actualizando etiqueta {$request['etiqueta']}...");
                $pedido->update([
                    'id_usuario'    => $request['id_usuario'],
                    'etapa'         => $request['etapa'],
                    'etiqueta'      => $request['etiqueta'],
                    'barcode'       => $request['barcode'],
                    'cantidad'      => $request['cantidad'],
                ]);
                if ($request['etapa'] !== $grupo->etapa) {
                    Log::info("Actualizando grupo {$pedido->id_grupo}...");
                    $grupo->update([
                        'etapa'         => $request['etapa'],
                    ]);
                    Log::info("Grupo {$pedido->id_grupo} actualizado.");
                } else {
                    Log::info("Grupo ya se encuentra en la nueva etapa [{$pedido->id_grupo}].");
                }
                return $this->show($pedido->id_usuario, $pedido->etapa);
            }
        } catch (ModelNotFoundException $e) {
            Log::info("Etiqueta {$request['etiqueta']} no encontrada.");
            if ($request['etapa'] ?? '' !== 'EP') {
                return $this->setResponseErrBusiness(ErrorCodes::ETIQUETA_ESTADO_ERRONEO);
            }
        } catch (Throwable $e) {
            Log::error("Falla al buscar la etiqueta. $e");
            return $this->setResponseErr($e, Response::HTTP_CONFLICT);
        }

        try {
            $grupo = GrupoPedido::where('etapa', $request['etapa'])->first();
            if ($grupo == null) {
                Log::info('Creando grupo de pedido');
                $grupo = GrupoPedido::create(
                    [
                        'foto'          => '',
                        'etapa'         => $request['etapa'],
                        'id_usuario'    => $request['id_usuario'],
                    ]
                );
            } else {
                Log::info($grupo);
            }
            $tracking = $grupo->pedidos->count() + 1;
            $pedido = Pedido::create([
                'id_grupo'      => $grupo->id,
                'tracking'      => $tracking,
                'id_usuario'    => $request['id_usuario'],
                'etapa'         => 'EP',
                'etiqueta'      => $request['etiqueta'],
                'barcode'       => $request['barcode'],
                'cantidad'      => $request['cantidad'],
            ]);
            Log::info("Pedido creado.");
            return $this->show($pedido->id_usuario, $pedido->etapa);
        } catch (ModelNotFoundException $e) {
            return $this->setResponseErr($e, Response::HTTP_NO_CONTENT);
        } catch (Throwable $e) {
            return $this->setResponseErr($e, ErrorCodes::SHOW_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($userId, $etapa)
    {
        try {
            Log::info("Listando registros [$userId] - [$etapa]");
            $pedido = Pedido::where('id_usuario', $userId)
                    ->where('etapa', $etapa)
                    ->get();
            return $this->responseOK($pedido);
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
        Log::info("Actualizando pedido.");
        try {
            $request->validate([
                'id_usuario'    => 'required|numeric',
                'etiqueta'      => 'required',
                'barcode'       => 'required',
                'cantidad'      => 'required|numeric',
                'etapa'         => 'required',
            ]);
        } catch(Throwable $e) {
            Log::error("Falla en la validación.");
            return $this->setResponseErr($e,
                Response::HTTP_BAD_REQUEST
                // ErrorCodes::VALIDATION_ERROR
            );
        }

        try {
            $pedido = Pedido::findOrFail($id);
            $pedido->update($request->all());
            Log::info("Pedido actualizado.");
            return $this->show($pedido->id_usuario, $pedido->etapa);
        } catch (ModelNotFoundException $e) {
            return $this->setResponseErr($e, Response::HTTP_NO_CONTENT);
        } catch (Throwable $e) {
            return $this->setResponseErr($e, ErrorCodes::SHOW_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Log::info("Actualizando pedido.");
        try {
            $pedido = Pedido::findOrFail($id);
            $pedido->delete();
            Log::info("Pedido eliminado.");
            return $this->show($pedido->id_usuario, $pedido->etapa);
        } catch (ModelNotFoundException $e) {
            return $this->setResponseErr($e, Response::HTTP_NO_CONTENT);
        } catch (Throwable $e) {
            return $this->setResponseErr($e, ErrorCodes::SHOW_ERROR);
        }
    }

    public function listarEtiquetas(int $grupoId) {
        try {
            Log::info("Listando tracking del grupo [$grupoId]");
            $tracking = Order::with('product')
                    ->where('Orders_group_id', $grupoId)
                    ->get();
            return $this->responseOK($tracking);
        } catch (ModelNotFoundException $e) {
            return $this->setResponseErr($e, Response::HTTP_NO_CONTENT);
        } catch (Throwable $e) {
            return $this->setResponseErr($e, ErrorCodes::SHOW_ERROR);
        }
    }

    /**
     * Consulta si la etiqueta pertenece al grupo.
     */
    public function validarEtiqueta(int $grupoId, int $etiqueta) {
        try {
            Log::info("Validando etiqueta $etiqueta para el grupo [$grupoId]");
            $etiqueta = Order::with('product')
                    ->where('Tracking_code', $etiqueta)
                    ->firstOrFail();
            if ($etiqueta->Orders_group_id != $grupoId) {
                return $this->setResponseErrBusiness(ErrorCodes::ETIQUETA_OTHER_GROUP, Response::HTTP_PRECONDITION_FAILED);
            }
            return $this->responseOK($etiqueta);
        } catch (ModelNotFoundException $e) {
            return $this->setResponseErrBusiness(ErrorCodes::ETIQUETA_NOT_FOUND, Response::HTTP_PRECONDITION_FAILED);
        } catch (Throwable $e) {
            return $this->setResponseErr($e, ErrorCodes::SHOW_ERROR);
        }
    }

    public function despacharEtiqueta(Request $request) {
        try {
            $request->validate([
                'Product_quantity'  => 'required|numeric',
                'Tracking_code'     => 'required',
                'SKU_id'            => 'required',
                'Orders_group_id'   => 'required|numeric',
            ]);
        } catch(Throwable $e) {
            Log::error("Falla en la validación al despachar etiqueta.");
            return $this->setResponseErr($e, Response::HTTP_BAD_REQUEST);
        }

        try {
            $tracking = $request->all();
            Log::info("Despachando etiqueta " . json_encode($tracking));
            $tracking['Order_state'] = Constants::DESPACHADO;
            $etiqueta = Order::where('Tracking_code', $request['Tracking_code'])
                    ->firstOrFail();
            if ($etiqueta->Orders_group_id != $request['Orders_group_id']) {
                return $this->setResponseErrBusiness(ErrorCodes::ETIQUETA_OTHER_GROUP, Response::HTTP_PRECONDITION_FAILED);
            }
            if ($etiqueta->Product_quantity != $request['Product_quantity']) {
                return $this->setResponseErrBusiness(ErrorCodes::ETIQUETA_QUANTITY_DIFFERS, Response::HTTP_PRECONDITION_FAILED);
            }
            $etiqueta->update($tracking);
            return $this->setResponse($etiqueta, trans(ErrorCodes::ETIQUETA_DESPACHADA_OK));
        } catch (ModelNotFoundException $e) {
            return $this->setResponseErrBusiness(ErrorCodes::ETIQUETA_NOT_FOUND, Response::HTTP_PRECONDITION_FAILED);
        } catch (Throwable $e) {
            return $this->setResponseErr($e, ErrorCodes::SHOW_ERROR);
        }
    }

    public function entregarEtiqueta(Request $request) {
        try {
            $request->validate([
                'Product_quantity'  => 'required|numeric',
                'Tracking_code'     => 'required',
                'SKU_id'            => 'required',
                'Orders_group_id'   => 'required|numeric',
            ]);
        } catch(Throwable $e) {
            Log::error("Falla en la validación al entregar etiqueta.");
            return $this->setResponseErr($e, Response::HTTP_BAD_REQUEST);
        }

        try {
            $tracking = $request->all();
            Log::info("Etiqueta a entregar " . json_encode($tracking));
            $tracking['Order_state'] = Constants::ENTREGADO;
            $etiqueta = Order::where('Tracking_code', $request['Tracking_code'])
                    ->firstOrFail();
            if ($etiqueta->Orders_group_id != $request['Orders_group_id']) {
                return $this->setResponseErrBusiness(ErrorCodes::ETIQUETA_OTHER_GROUP, Response::HTTP_PRECONDITION_FAILED);
            }
            if ($etiqueta->Product_quantity != $request['Product_quantity']) {
                return $this->setResponseErrBusiness(ErrorCodes::ETIQUETA_QUANTITY_DIFFERS, Response::HTTP_PRECONDITION_FAILED);
            }
            $etiqueta->update($tracking);
            return $this->setResponse($etiqueta, trans(ErrorCodes::ETIQUETA_ENTREGADA_OK));
        } catch (ModelNotFoundException $e) {
            return $this->setResponseErrBusiness(ErrorCodes::ETIQUETA_NOT_FOUND, Response::HTTP_PRECONDITION_FAILED);
        } catch (Throwable $e) {
            return $this->setResponseErr($e, ErrorCodes::SHOW_ERROR);
        }
    }

    private function siguienteEtapa(string $etapaEtiqueta, string $etapaActual) {
        if ($etapaActual === 'EP') {
            return 'E';
        } else if ($etapaActual === 'E') {
            return 'D';
        }
    }
}
