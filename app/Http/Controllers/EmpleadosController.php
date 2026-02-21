<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator; // Necesario para validar

class EmpleadosController extends Controller
{
    public function index(Request $request)
    {
        $empleados = Empleado::all();

        // Si la petición viene de la API, devolvemos JSON
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json($empleados, 200);
        }

        return view('administradores.listado', [
            'administradores' => $empleados
        ]);
    }

    // Nuevo método para la API: Mostrar un solo registro
    public function show($id)
    {
        $empleado = Empleado::find($id);
        if (!$empleado) {
            return response()->json(['error' => 'No encontrado'], 404);
        }
        return response()->json($empleado, 200);
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
        // --- VALIDACIONES ---
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:empleados,email',
            'puesto' => 'required|string',
            'usuario' => 'required|unique:empleados,usuario',
            'contrasena' => 'required|min:4',
            'salario' => 'required|numeric',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        // --------------------

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

        // Respuesta para API
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Creado con éxito', 'data' => $empleado], 201);
        }

        return redirect('/admins/listado')->with('status', 'Administrador creado con éxito');
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        // --- VALIDACIONES ---
        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email|unique:empleados,email,' . $id . ',id_empleado',
            'usuario' => 'nullable|unique:empleados,usuario,' . $id . ',id_empleado',
            'salario' => 'nullable|numeric',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        // --------------------

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

        // Respuesta para API
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Actualizado con éxito', 'data' => $empleado], 200);
        }

        return redirect('/admins/listado')->with('status', 'Administrador actualizado');
    }

    // Nuevo método para la API: Eliminar
    public function destroy($id)
    {
        $empleado = Empleado::find($id);
        if (!$empleado) {
            return response()->json(['error' => 'No encontrado'], 404);
        }

        if ($empleado->imagen && File::exists(public_path($empleado->imagen))) {
            File::delete(public_path($empleado->imagen));
        }

        $empleado->delete();
        return response()->json(['message' => 'Eliminado con éxito'], 200);
    }
}