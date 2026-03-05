<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required',
            'contrasena' => 'required',
        ]);

        // 1. Intentar buscar en Empleados
        $user = Empleado::where('usuario', $request->usuario)->first();
        $tipo = 'Empleado';

        // 2. Si no es empleado, intentar buscar en Clientes
        if (!$user) {
            $user = Cliente::where('usuario', $request->usuario)->first();
            $tipo = 'Cliente';
        }

        // 3. Validar si el usuario existe y la contraseña coincide
        if (!$user || $request->contrasena !== $user->contrasena) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        // --- SEGURIDAD: Eliminar tokens anteriores antes de crear uno nuevo ---
        $user->tokens()->delete(); 

        // 4. Generar el Token (Sanctum)
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user_type' => $tipo,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        // Elimina solo el token que se está usando actualmente
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'contrasena_actual' => 'required',
            'nueva_contrasena' => 'required|min:4',
        ]);

        $user = $request->user(); 
        $tabla = $user->getTable();
        $columnaId = ($tabla === 'clientes') ? 'id_cliente' : 'id_empleado';
        $idValor = $user->getKey();

        // Validar contraseña actual en texto plano
        if ($request->contrasena_actual !== $user->contrasena) {
            return response()->json(['message' => 'La contraseña actual es incorrecta'], 422);
        }

        // Actualización directa en la base de datos
        DB::table($tabla)
            ->where($columnaId, $idValor)
            ->update(['contrasena' => $request->nueva_contrasena]);

        // --- SEGURIDAD EXTRA: Cerrar todas las sesiones al cambiar la contraseña ---
        // Esto obliga al usuario a volver a loguearse con su nueva clave
        $user->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Contraseña actualizada con éxito. Por seguridad, debe iniciar sesión nuevamente.'
        ]);
    }
}