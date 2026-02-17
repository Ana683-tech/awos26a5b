<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SoloClientes
{
   public function handle($request, $next) {
    if (session('tipo') === 'cliente' && session()->has('cliente')) {
        return $next($request);
    }
    return redirect('/')->with('error', 'Debes iniciar sesión como cliente.');
}
}
