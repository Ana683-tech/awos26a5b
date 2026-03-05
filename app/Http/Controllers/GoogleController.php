<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\Empleado;
use App\Models\Cliente;
use Illuminate\Support\Facades\Config;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            // Limpiamos y convertimos a minúsculas para evitar errores de escritura
            $email = strtolower(trim($googleUser->email));

            
            // Buscamos en la tabla empleados ignorando espacios y mayúsculas
            $empleado = Empleado::whereRaw('LOWER(TRIM(email)) = ?', [$email])->first();

            if ($empleado) {
                // Si existe en la tabla, verificamos si está activo
                if ($empleado->estado == 0) {
                    return redirect('/')->with('error', 'Acceso denegado: Tu cuenta de empleado está INACTIVA.');
                }

                // Si está activo, crea sesión como empleado (sea @gmail o @soy.utj)
                session(['empleado' => $empleado, 'tipo' => 'empleado']);
                return redirect()->route('inicio');
            }

            // 2. SEGUNDA OPCIÓN: ¿Es un CLIENTE?
            $cliente = Cliente::whereRaw('LOWER(TRIM(email)) = ?', [$email])->first();
            
            if ($cliente) {
                session(['cliente' => $cliente, 'tipo' => 'cliente']);
                return redirect()->route('welcome');
            }

            // 3. Si no está en ninguna tabla
            return redirect('/')->with('error', 'El correo ' . $email . ' no está registrado en el sistema.');

        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Error de conexión: ' . $e->getMessage());
        }
    }
}