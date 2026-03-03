<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpleadosController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\ClientesController;  
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\Api\ApiAuthController;

// --- RUTAS PÚBLICAS ---
Route::post('/login', [ApiAuthController::class, 'login']);

// --- RUTAS PROTEGIDAS POR TOKEN (SANCTUM) ---
Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/logout', [ApiAuthController::class, 'logout']);

    // --- RUTAS DE ADMINISTRADORES (EMPLEADOS) ---
    Route::get('/administradores', [EmpleadosController::class, 'index']);      // Listar
    Route::post('/administradores', [EmpleadosController::class, 'store']);     // Crear
    Route::get('/administradores/{id}', [EmpleadosController::class, 'show']);   // Consultar uno
    Route::put('/administradores/{id}', [EmpleadosController::class, 'update']); // Editar
    Route::delete('/administradores/{id}', [EmpleadosController::class, 'destroy']); // Eliminar

    // --- RUTAS DE PRODUCTOS (GESTIÓN DE IMÁGENES) ---
    Route::get('/productos', [ProductosController::class, 'index']);      // Listar
    Route::post('/productos', [ProductosController::class, 'store']);     // Crear
    Route::get('/productos/{id}', [ProductosController::class, 'show']);   // Consultar uno
    Route::put('/productos/{id}', [ProductosController::class, 'update']); // Editar
    Route::delete('/productos/{id}', [ProductosController::class, 'destroy']); // Eliminar

    // --- RUTAS DE CLIENTES ---
    Route::get('/clientes', [ClientesController::class, 'index']);      // Listar
    Route::post('/clientes', [ClientesController::class, 'store']);     // Crear
    Route::get('/clientes/{id}', [ClientesController::class, 'show']);   // Consultar uno
    Route::put('/clientes/{id}', [ClientesController::class, 'update']); // Editar
    Route::delete('/clientes/{id}', [ClientesController::class, 'destroy']); // Eliminar

    // --- RUTAS DE PEDIDOS ---
    Route::get('/pedidos', [PedidosController::class, 'index']);        // Listar
    Route::post('/pedidos', [PedidosController::class, 'store']);       // Crear
    Route::get('/pedidos/{id}', [PedidosController::class, 'show']);     // Consultar uno
    Route::delete('/pedidos/{id}', [PedidosController::class, 'destroy']); // Eliminar
    
    
});