<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\Cliente;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required',
            'contrasena' => 'required'
        ]);

        $cliente = Cliente::where('usuario', $request->usuario)->first();

        if ($cliente && $request->contrasena === $cliente->contrasena) {
            session(['cliente' => $cliente, 'tipo' => 'cliente']);
            return redirect()->route('welcome');
        }

        $esEmpleado = Empleado::where('usuario', $request->usuario)->first();
        if ($esEmpleado) {
     
            if ($esEmpleado->estado == 0) {
                return back()->with('error', 'Tu cuenta está inactiva. Contacta al Gerente.');
            }
            return back()->with('error', 'Los empleados deben entrar con el botón de Google.');
        }

        return back()->with('error', 'Usuario o contraseña incorrectos.');
    }
}