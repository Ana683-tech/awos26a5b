<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required',
            'contrasena' => 'required',
        ]);

        // Buscamos al empleado por el campo usuario
        $empleado = Empleado::where('usuario', $request->usuario)->first();

        // Validamos contraseña (asumiendo que usas Hash::make en el registro)
       if (!$empleado || $request->contrasena !== $empleado->contrasena)  {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        $empleado->tokens()->delete();

        // Generamos el token
        $token = $empleado->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $empleado
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Token eliminado']);
    }
}
//--------------------------------------------------------------------------------------
