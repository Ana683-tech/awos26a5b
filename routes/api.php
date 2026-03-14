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
Route::post('/logout', [ApiAuthController::class, 'logout']);
Route::post('/change-password', [ApiAuthController::class, 'changePassword']);


// --- RUTAS DE ADMINISTRADORES (EMPLEADOS) ---
Route::get('/administradores', [EmpleadosController::class, 'index']);      
Route::post('/administradores', [EmpleadosController::class, 'store']);     
Route::get('/administradores/{id}', [EmpleadosController::class, 'show']);   
Route::put('/administradores/{id}', [EmpleadosController::class, 'update']); 
Route::delete('/administradores/{id}', [EmpleadosController::class, 'destroy']); 


// --- RUTAS DE PRODUCTOS ---
Route::get('/productos', [ProductosController::class, 'index']);      
Route::post('/productos', [ProductosController::class, 'store']);     
Route::get('/productos/{id}', [ProductosController::class, 'show']);   
Route::put('/productos/{id}', [ProductosController::class, 'update']); 
Route::delete('/productos/{id}', [ProductosController::class, 'destroy']); 


// --- RUTAS DE CLIENTES ---
Route::get('/clientes', [ClientesController::class, 'index']);      
Route::post('/clientes', [ClientesController::class, 'store']);     
Route::get('/clientes/{id}', [ClientesController::class, 'show']);   
Route::put('/clientes/{id}', [ClientesController::class, 'update']); 
Route::delete('/clientes/{id}', [ClientesController::class, 'destroy']); 


// --- RUTAS DE PEDIDOS (CRUD COMPLETO) ---
Route::get('/pedidos', [PedidosController::class, 'index']);
Route::post('/pedidos', [PedidosController::class, 'store']);
Route::get('/pedidos/{id}', [PedidosController::class, 'show']);
Route::put('/pedidos/{id}', [PedidosController::class, 'update']);
Route::delete('/pedidos/{id}', [PedidosController::class, 'destroy']); 
Route::get('clientes/{idCliente}/pedidos', [PedidosController::class, 'pedidosPorCliente']);