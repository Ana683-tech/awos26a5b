<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;

class PedidosController extends Controller
{
    // Listar todos los pedidos (GET)
    public function index()
    {
        $pedidos = Pedido::with('cliente')->get();
        return response()->json([
            'status' => true,
            'data' => $pedidos
        ], 200);
    }

    // Crear un nuevo pedido (POST) - MANTENGO TU LÓGICA
    public function store(Request $request)
    {
        $request->validate([
            'id_pedido' => 'required|unique:pedidos',
            'id_cliente' => 'required|exists:clientes,id_cliente',
            'fecha_pedido' => 'required|date',
            'total' => 'required|numeric'
        ]);

        $pedido = Pedido::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Pedido creado correctamente',
            'data' => $pedido
        ], 201);
    }

    // ... (Mantengo tu show, update, destroy igual) ...

    // --- NUEVOS MÉTODOS PARA ACTIVIDAD 23 ---

    // Realizar pago (POST /api/pagar-pedido/{id})
    public function realizarPago($id)
    {
        $pedido = Pedido::find($id);
        if (!$pedido) {
            return response()->json(['status' => false, 'message' => 'Pedido no encontrado'], 404);
        }
        
        $pedido->update(['estado_pago' => 1]); // 1 = Pagado
        
        return response()->json([
            'status' => true,
            'message' => 'Pago realizado con éxito',
            'data' => $pedido
        ], 200);
    }

    // Consultar estado de pago (GET /api/estado-pago/{id})
    public function consultarPago($id)
    {
        $pedido = Pedido::find($id);
        if (!$pedido) {
            return response()->json(['status' => false, 'message' => 'Pedido no encontrado'], 404);
        }
        
        $estado = ($pedido->estado_pago == 1) ? 'Pagado' : 'Pendiente';
        
        return response()->json([
            'status' => true,
            'pedido_id' => $id,
            'estado_pago' => $estado
        ], 200);
    }
}