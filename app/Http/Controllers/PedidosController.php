<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Producto;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PedidosController extends Controller
{
    public function index(Request $request) {
        $pedidos = Pedido::with('detalles')->orderBy('fecha_pedido', 'desc')->get();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => true,
                'data' => $pedidos
            ], 200);
        }

        return view('administradores.listado-pedidos', compact('pedidos'));
    }

    public function create() {
        $productos = Producto::all();
        $clientes = Cliente::all();
        return view('administradores.formulario-pedido', compact('productos', 'clientes'));
    }

    public function show(Request $request, $id) {
        try {
            $pedido = Pedido::with('detalles.producto')->find($id);

            if (!$pedido) {
                return response()->json([
                    'status' => false, 
                    'message' => 'Pedido no encontrado'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'pedido' => $pedido,
                'data' => $pedido
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false, 
                'message' => 'Error al consultar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request) {
        try {
            DB::beginTransaction();

            // A. Crear el encabezado del pedido
            $pedido = Pedido::create([
                'id_pedido'      => $request->id_pedido,
                'id_cliente'     => $request->id_cliente,
                'fecha_pedido'   => now(),
                'total'          => $request->total,
                'metodo_pago'    => $request->metodo_pago ?? 'Efectivo',
                'estado_pago'    => 'Pendiente',
                'direccion_envio'=> $request->direccion_envio ?? 'Entrega en tienda'
            ]);

            // B. Guardar productos y DESCONTAR STOCK
            if ($request->has('productos')) {
                foreach ($request->productos as $item) {
                    $producto = Producto::find($item['id_producto']);

                    // VALIDACIÓN DE STOCK
                    if (!$producto || $producto->stock < $item['cantidad']) {
                        DB::rollBack();
                        return response()->json([
                            'status' => false,
                            'message' => "Stock insuficiente para: " . ($producto->nombre ?? 'Producto') . ". Disponible: " . ($producto->stock ?? 0)
                        ], 400);
                    }

                    // RESTAR AL STOCK
                    $producto->stock -= $item['cantidad'];
                    $producto->save();

                    DetallePedido::create([
                        'id_pedido'       => $request->id_pedido,
                        'id_producto'     => $item['id_producto'],
                        'cantidad'        => $item['cantidad'],
                        'precio_unitario' => $item['precio']
                    ]);
                }
            }

            DB::commit();
            
            return response()->json([
                'status' => true, 
                'message' => 'Pedido #' . $request->id_pedido . ' guardado y stock actualizado',
                'data' => $pedido->load('detalles')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error en PedidosController@store: " . $e->getMessage());
            return response()->json([
                'status' => false, 
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id) {
        $pedido = Pedido::find($id);
        if (!$pedido) {
            return response()->json(['status' => false, 'message' => 'No encontrado'], 404);
        }
        $pedido->update($request->all());
        return response()->json(['status' => true, 'message' => 'Actualizado', 'data' => $pedido], 200);
    }

    // --- MÉTODO DESTROY MODIFICADO PARA DEVOLVER STOCK ---
    public function destroy($id) {
        try {
            DB::beginTransaction();

            // 1. Buscamos el pedido con sus detalles para saber qué productos devolver
            $pedido = Pedido::with('detalles')->find($id);

            if (!$pedido) {
                return response()->json(['status' => false, 'message' => 'No encontrado'], 404);
            }

            // 2. REPOSICIÓN DE STOCK: Recorremos los detalles y sumamos al stock del producto
            if ($pedido->detalles) {
                foreach ($pedido->detalles as $detalle) {
                    $producto = Producto::find($detalle->id_producto);
                    if ($producto) {                       $producto->stock += $detalle->cantidad;
                        $producto->save();
                    }
                }
            }

            // 3. Eliminamos detalles y el pedido
            DetallePedido::where('id_pedido', $id)->delete();
            $pedido->delete();

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Pedido cancelado y stock restablecido'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al eliminar pedido: " . $e->getMessage());
            return response()->json([
                'status' => false, 
                'message' => 'Error al cancelar: ' . $e->getMessage()
            ], 500);
        }
    }
// --- NUEVO MÉTODO PARA LISTAR PEDIDOS DE UN CLIENTE ESPECÍFICO ---
    public function pedidosPorCliente($idCliente) {
        try {
            $pedidos = Pedido::with('detalles.producto')
                ->where('id_cliente', $idCliente)
                ->orderBy('fecha_pedido', 'desc')
                ->get();

            if ($pedidos->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No se encontraron pedidos para el cliente: ' . $idCliente
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $pedidos
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false, 
                'message' => 'Error al consultar pedidos del cliente: ' . $e->getMessage()
            ], 500);
        }
    }

}