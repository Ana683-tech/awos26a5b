<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class EmpleadosController extends Controller
{
    public function index(Request $request)
    {
        $empleados = Empleado::all();

        $empleados->transform(function ($empleado) {
            if ($empleado->imagen) {
                $empleado->imagen = asset($empleado->imagen);
            }
            return $empleado;
        });

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json($empleados, 200);
        }

        return view('administradores.listado', [
            'administradores' => $empleados
        ]);
    }

    // Para "Nuevo Registro"
    public function create()
    {
        return view('administradores.formulario-crear'); 
    }

    // Para "Editar Perfil"
    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id);
        return view('administradores.formulario-editar', compact('empleado'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'apellido' => 'required',
            'email' => 'required|email',
            'puesto' => 'required',
            'usuario' => 'required',
            'contrasena' => 'required',
            'salario' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json($validator->errors(), 400);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $imagenPath = null;

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombreLimpio = preg_replace('/[^A-Za-z0-9.\-]/', '_', $file->getClientOriginalName());
            $nombreImagen = time() . '_' . $nombreLimpio;
            $file->move(public_path('imagenes/administradores'), $nombreImagen);
            $imagenPath = 'imagenes/administradores/' . $nombreImagen;
        }

        $empleado = Empleado::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'puesto' => $request->puesto,
            'fecha_contratacion' => $request->fecha_contratacion,
            'usuario' => $request->usuario,
            'contrasena' => $request->contrasena,
            'salario' => $request->salario,
            'imagen' => $imagenPath,
            'estado' => 1
        ]);

        // RESPUESTA DINÁMICA: No afecta a Postman
        if ($request->wantsJson() || $request->is('api/*')) {
            if ($empleado->imagen) {
                $empleado->imagen = asset($empleado->imagen);
            }
            return response()->json($empleado, 201);
        }

        // Para el navegador: Redirigir al listado
        return redirect('/admins/listado')->with('success', 'Empleado creado con éxito');
    }

    public function show($id)
    {
        $empleado = Empleado::find($id);

        if (!$empleado) {
            return response()->json(['error' => 'No encontrado'], 404);
        }

        if ($empleado->imagen) {
            $empleado->imagen = asset($empleado->imagen);
        }

        return response()->json($empleado, 200);
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::find($id);

        if (!$empleado) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'No encontrado'], 404);
            }
            return redirect()->back()->with('error', 'Empleado no encontrado');
        }

        if ($request->hasFile('imagen')) {
            if ($empleado->imagen && File::exists(public_path($empleado->imagen))) {
                File::delete(public_path($empleado->imagen));
            }

            $file = $request->file('imagen');
            $nombreLimpio = preg_replace('/[^A-Za-z0-9.\-]/', '_', $file->getClientOriginalName());
            $nombreImagen = time() . '_' . $nombreLimpio;
            $file->move(public_path('imagenes/administradores'), $nombreImagen);
            $empleado->imagen = 'imagenes/administradores/' . $nombreImagen;
        }

        $empleado->update($request->except('imagen'));

        if ($request->wantsJson() || $request->is('api/*')) {
            if ($empleado->imagen) {
                $empleado->imagen = asset($empleado->imagen);
            }
            return response()->json($empleado, 200);
        }

        return redirect('/admins/listado')->with('success', 'Empleado actualizado correctamente');
    }

    public function destroy(Request $request, $id)
    {
        $empleado = Empleado::find($id);

        if (!$empleado) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'No encontrado'], 404);
            }
            return redirect()->back()->with('error', 'Empleado no encontrado');
        }

        if ($empleado->imagen && File::exists(public_path($empleado->imagen))) {
            File::delete(public_path($empleado->imagen));
        }

        $empleado->delete();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(['mensaje' => 'Empleado eliminado'], 200);
        }

        return redirect()->back()->with('success', 'Empleado eliminado');
    }
}