<?php

namespace App\Http\Controllers;

use App\Constants\Constants;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Constants\ErrorCodes;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\CountForGroup;
use App\Models\Order;
use App\Models\OrderGroup;
// use App\Models\Order;
// use App\Constants\Constants;
use Illuminate\Support\Facades\DB;

class GrupoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function show(string $etapa)
    {
        try {
            Log::info("Listando grupos con estado [$etapa]");
            $grupo = OrderGroup::with(['courier', 'marketplace'])
                ->whereIn('id', function ($query) {
                    $query->select('Orders_group_id')
                        ->from('Sales_Orders_count_for_group')
                        ->whereRaw('count_quantity > (SELECT SUM("Product_quantity") FROM "Sales_Orders_order" Z WHERE Z."SKU_id" = "Sales_Orders_count_for_group"."SKU_id" AND Z."Orders_group_id" = "Sales_Orders_count_for_group"."Orders_group_id")');
                })->get();
            Log::info('Registros encontrados ' . $grupo->count());
            return $this->responseOK($grupo);
        } catch (ModelNotFoundException $e) {
            return $this->setResponseErr($e, Response::HTTP_NO_CONTENT);
        } catch (Throwable $e) {
            return $this->setResponseErr($e, ErrorCodes::SHOW_ERROR);
        }
    }

    /**
     *
     */
    public function skus($id) {
        try {
            Log::info("Listando skus del grupo [$id]");
            // $skus = CountForGroup::withSum('order', 'Product_quantity')
            //     ->with('product')
            //     ->where('Orders_group_id', $id)
            //     ->get();
            $skus = CountForGroup::join('Sales_Orders_order', function ($join) {
                    $join->on('Sales_Orders_count_for_group.SKU_id', '=', 'Sales_Orders_order.SKU_id')
                        ->on('Sales_Orders_count_for_group.Orders_group_id', '=', 'Sales_Orders_order.Orders_group_id');
                })
                ->with('product')
                ->where('Sales_Orders_count_for_group.Orders_group_id', $id)
                ->groupBy(
                        'Sales_Orders_count_for_group.SKU_id',
                        'Sales_Orders_count_for_group.Orders_group_id',
                        'Sales_Orders_count_for_group.count_quantity',
                        )
                ->select(
                    'Sales_Orders_count_for_group.SKU_id',
                    'Sales_Orders_count_for_group.Orders_group_id',
                    'Sales_Orders_count_for_group.count_quantity',
                    DB::raw('SUM("Sales_Orders_order"."Product_quantity") as order_sum_product_quantity')
                )
                ->get();
            Log::info('Registros encontrados ' . $skus->count());
            return $this->responseOK($skus);
        } catch (ModelNotFoundException $e) {
            return $this->setResponseErr($e, Response::HTTP_NO_CONTENT);
        } catch (Throwable $e) {
            return $this->setResponseErr($e, ErrorCodes::SHOW_ERROR);
        }
    }

    public function store(Request $request) {
        try {
            $request->validate([
                'Product_quantity'  => 'required|numeric',
                'Order_state'       => 'required',
                'Tracking_code'     => 'required',
                'SKU_id'            => 'required',
                'Orders_group_id'   => 'required',
            ]);
        } catch(Throwable $e) {
            return $this->setResponseErr($e, Response::HTTP_BAD_REQUEST);
        }
        try {
            Log::info("Creando registro... [" . json_encode($request) . "]");
            $data = $request->all();
            $data['created_date'] = now();
            $order = Order::create(
                $data
            );
            Log::info('Registro creado ' . json_encode($order));
            return $this->responseOK($order);
        } catch (ModelNotFoundException $e) {
            return $this->setResponseErr($e, Response::HTTP_NO_CONTENT);
        } catch (Throwable $e) {
            return $this->setResponseErr($e, ErrorCodes::SHOW_ERROR);
        }
    }

    /**
     * Lista todos los grupos que están en estado 'En preparación' y 'Despachado'.
     */
    public function listarDespachar()
    {
        try {
            Log::info("Listando grupos en estado 'En preparación' y 'Despachado'.");
            $grupo = OrderGroup::with(['courier', 'marketplace'])
                ->whereIn('id', function ($query) {
                    $query->select('Orders_group_id')
                        ->from('Sales_Orders_order')
                        ->whereIn('Order_state', ['En preparación']);
                })->get();
            Log::info('Registros encontrados ' . $grupo->count());
            return $this->responseOK($grupo);
        } catch (ModelNotFoundException $e) {
            return $this->setResponseErr($e, Response::HTTP_NO_CONTENT);
        } catch (Throwable $e) {
            return $this->setResponseErr($e, ErrorCodes::SHOW_ERROR);
        }
    }

    public function listarGruposDespachado() {
        try {
            Log::info("Listando grupos en estado 'Despachado'.");
            $grupo = OrderGroup::with(['courier', 'marketplace'])
                ->whereIn('id', function ($query) {
                    $query->select('Orders_group_id')
                        ->from('Sales_Orders_order')
                        ->where('Order_state', Constants::DESPACHADO);
                })->get();
            Log::info('Registros encontrados ' . $grupo->count());
            return $this->responseOK($grupo);
        } catch (ModelNotFoundException $e) {
            return $this->setResponseErr($e, Response::HTTP_NO_CONTENT);
        } catch (Throwable $e) {
            return $this->setResponseErr($e, ErrorCodes::SHOW_ERROR);
        }
    }
}
