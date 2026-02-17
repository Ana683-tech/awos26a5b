<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Empleado;

class EsGerente
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('empleado')) {
            return redirect('/')->with('error', 'Debes iniciar sesión primero.');
        }

      
        $id = session('empleado')->id_empleado;
        $check = Empleado::find($id);

        if (!$check || $check->estado == 0) {
            session()->flush();
            return redirect('/')->with('error', 'Cuenta inactiva.');
        }

        $puesto = strtolower($check->puesto);

        if ($puesto === 'gerente') {
            return $next($request);
        }

        return redirect()->route('inicio')->with('error', 'Solo el Gerente tiene acceso aquí.');
    }
}