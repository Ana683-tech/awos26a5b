<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpleadosController;

use App\Http\Controllers\ProductosController;
use App\Http\Controllers\ClientesController;  

//nuevo
use App\Http\Controllers\PedidosController;


// Esta sola línea crea rutas para:
// GET /api/administradores (index)
// POST /api/administradores (store)
// GET /api/administradores/{id} (show)
// PUT /api/administradores/{id} (update)
// DELETE /api/administradores/{id} (destroy)

Route::apiResource('administradores', EmpleadosController::class);
Route::apiResource('productos', ProductosController::class);
Route::apiResource('clientes', ClientesController::class);

// NUEVO: Rutas para Pedidos
Route::apiResource('pedidos', PedidosController::class);
