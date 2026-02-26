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

//____________________________________________________


   //NUEVO CONTROLADOR PARA PEDIDOS


//______________________________________________________


    // Crear un nuevo pedido (POST)
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

    // Mostrar un pedido específico (GET {id})
    public function show($id)
    {
        $pedido = Pedido::with('cliente')->find($id);

        if (!$pedido) {
            return response()->json(['status' => false, 'message' => 'Pedido no encontrado'], 404);
        }

        return response()->json(['status' => true, 'data' => $pedido], 200);
    }

    // Actualizar un pedido (PUT)
    public function update(Request $request, $id)
    {
        $pedido = Pedido::find($id);

        if (!$pedido) {
            return response()->json(['status' => false, 'message' => 'Pedido no encontrado'], 404);
        }

        $pedido->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Pedido actualizado',
            'data' => $pedido
        ], 200);
    }

    // Eliminar un pedido (DELETE)
    public function destroy($id)
    {
        $pedido = Pedido::find($id);

        if (!$pedido) {
            return response()->json(['status' => false, 'message' => 'Pedido no encontrado'], 404);
        }

        $pedido->delete();

        return response()->json([
            'status' => true,
            'message' => 'Pedido eliminado correctamente'
        ], 200);
    }
}