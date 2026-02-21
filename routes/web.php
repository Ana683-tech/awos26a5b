<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpleadosController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    if (session()->has('empleado')) return redirect()->route('inicio');
    if (session()->has('cliente')) return redirect()->route('welcome');
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.validar');
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/logout', function () {
    session()->flush();
    return redirect('/')->with('error', 'Sesión cerrada.');
})->name('logout');

Route::middleware(['es_empleado'])->group(function () {

    Route::get('/inicio', function () {
        return view('inicio');
    })->name('inicio');

  
    Route::get('/admins/listado', [EmpleadosController::class, 'index']);
    Route::get('/admins/registro', [EmpleadosController::class, 'create']);
    Route::post('/admins/store', [EmpleadosController::class, 'store'])->name('empleados.store');
    Route::get('/admins/{id}/edit', [EmpleadosController::class, 'edit'])->name('empleados.edit');
    Route::put('/admins/{id}', [EmpleadosController::class, 'update'])->name('empleados.update');

 
    Route::get('/client/listadocl', [ClientesController::class, 'index']);
    Route::view('/client/formulario-clien', '/administradores/formulario-clien');
    Route::post('/client/store', [ClientesController::class, 'store'])->name('clientes.store');
    Route::get('/client/{id}/edit', [ClientesController::class, 'edit'])->name('clientes.edit');
    Route::put('/client/{id}', [ClientesController::class, 'update'])->name('clientes.update');
    Route::delete('/client/{id}', [ClientesController::class, 'destroy'])->name('clientes.destroy');

    
    Route::get('/produc/productos', [ProductosController::class, 'index']);
    Route::post('/produc/store', [ProductosController::class, 'store'])->name('productos.store');
    Route::put('/produc/{id}', [ProductosController::class, 'update'])->name('productos.update');
    Route::delete('/produc/{id}', [ProductosController::class, 'destroy'])->name('productos.destroy');
    Route::view('/pedido/formulario-pedido', '/administradores/formulario-pedido');
});

Route::middleware(['es_cliente'])->group(function () {
    Route::get('/welcome', function () {
        return view('welcome');
    })->name('welcome');
    Route::post('/obtener-ubicacion', [ProductosController::class, 'obtenerUbicacion']);
    Route::get('/productos', [ProductosController::class, 'index'])->name('productos.index');
});


