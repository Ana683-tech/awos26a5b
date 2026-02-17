<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Empleado;

class SoloEmpleados
{
    public function handle($request, $next)
    {
        if (session('tipo') === 'empleado' && session()->has('empleado')) {
            
            
            $id = session('empleado')->id_empleado;
            $check = Empleado::find($id);

            if (!$check || $check->estado == 0) {
                session()->flush(); 
                return redirect('/')->with('error', 'Tu cuenta ha sido desactivada recientemente.');
            }

            return $next($request);
        }
        
        return redirect('/')->with('error', 'Zona exclusiva para empleados.');
    }
}