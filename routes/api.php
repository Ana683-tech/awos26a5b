<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpleadosController;

// Esta sola línea crea rutas para:
// GET /api/administradores (index)
// POST /api/administradores (store)
// GET /api/administradores/{id} (show)
// PUT /api/administradores/{id} (update)
// DELETE /api/administradores/{id} (destroy)
Route::apiResource('administradores', EmpleadosController::class);