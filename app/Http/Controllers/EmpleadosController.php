<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use Illuminate\Support\Facades\File;

class EmpleadosController extends Controller
{
    public function index()
    {
        $empleados = Empleado::all();
        return view('administradores.listado', [
            'administradores' => $empleados
        ]);
    }

    public function create()
    {
        return view('administradores.formulario-crear');
    }

    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id);
        return view('administradores.formulario-editar', [
            'empleado' => $empleado 
        ]);
    }

    public function store(Request $request)
    {
        $empleado = new Empleado();
        $empleado->nombre = $request->nombre;
        $empleado->apellido = $request->apellido;
        $empleado->email = $request->email;
        $empleado->puesto = $request->puesto;
        $empleado->usuario = $request->usuario;
        $empleado->salario = $request->salario;
        $empleado->contrasena = $request->contrasena;
        $empleado->estado = 1;

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $imagen->move(public_path('imagenes/administradores'), $nombreImagen);
            $empleado->imagen = 'imagenes/administradores/' . $nombreImagen;
        }

        $empleado->save();
        return redirect('/admins/listado')->with('status', 'Administrador creado con éxito');
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        $empleado->nombre = $request->nombre;
        $empleado->apellido = $request->apellido;
        
      
        if ($request->filled('email')) {
            $empleado->email = $request->email;
        }

        $empleado->puesto = $request->puesto;
        $empleado->usuario = $request->usuario;
        $empleado->salario = $request->salario;
        $empleado->estado = $request->estado; 

        if ($request->filled('contrasena')) {
            $empleado->contrasena = $request->contrasena;
        }

        if ($request->hasFile('imagen')) {
            if ($empleado->imagen && File::exists(public_path($empleado->imagen))) {
                File::delete(public_path($empleado->imagen));
            }
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $imagen->move(public_path('imagenes/administradores'), $nombreImagen);
            $empleado->imagen = 'imagenes/administradores/' . $nombreImagen;
        }

        $empleado->save();
        return redirect('/admins/listado')->with('status', 'Administrador actualizado');
    }
}