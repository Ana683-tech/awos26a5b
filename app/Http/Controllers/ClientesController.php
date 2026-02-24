<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ClientesController extends Controller
{
    public function index(Request $request)
    {
        $clientes = Cliente::all();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => true,
                'data' => $clientes
            ], 200);
        }

        return view('administradores.listadocl', compact('clientes'));
    }

    public function show($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json([
                'status' => false,
                'message' => 'Cliente no encontrado'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $cliente
        ], 200);
    }

    public function store(Request $request)
    {
        if ($request->wantsJson() || $request->is('api/*')) {

            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:255',
                'apellidos' => 'required|string|max:255',
                'telefono' => 'required',
                'email' => 'required|email|unique:clientes,email',
                'usuario' => 'required|unique:clientes,usuario',
                'contrasena' => 'required|min:4',
                'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
        }

        $cliente = new Cliente();
        $cliente->nombre = $request->nombre ?? $request->nombres;
        $cliente->apellidos = $request->apellidos;
        $cliente->telefono = $request->telefono;
        $cliente->email = $request->email ?? $request->correo;
        $cliente->usuario = $request->usuario;
        $cliente->contrasena = Hash::make($request->contrasena);

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = time().'_'.$imagen->getClientOriginalName();
            $imagen->move(public_path('imagenes/clientes'), $nombreImagen);
            $cliente->imagen = 'imagenes/clientes/'.$nombreImagen;
        }

        $cliente->save();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => true,
                'message' => 'Cliente creado correctamente',
                'data' => $cliente
            ], 201);
        }

        return redirect('/client/listadocl');
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        if ($request->wantsJson() || $request->is('api/*')) {

            $validator = Validator::make($request->all(), [
                'email' => 'nullable|email|unique:clientes,email,'.$id,
                'usuario' => 'nullable|unique:clientes,usuario,'.$id,
                'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
        }

        $cliente->nombre = $request->nombre;
        $cliente->apellidos = $request->apellidos;
        $cliente->telefono = $request->telefono;
        $cliente->email = $request->email;
        $cliente->usuario = $request->usuario;

        if ($request->filled('contrasena')) {
            $cliente->contrasena = Hash::make($request->contrasena);
        }

        if ($request->hasFile('imagen')) {
            if ($cliente->imagen && File::exists(public_path($cliente->imagen))) {
                File::delete(public_path($cliente->imagen));
            }

            $imagen = $request->file('imagen');
            $nombreImagen = time().'_'.$imagen->getClientOriginalName();
            $imagen->move(public_path('imagenes/clientes'), $nombreImagen);
            $cliente->imagen = 'imagenes/clientes/'.$nombreImagen;
        }

        $cliente->save();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => true,
                'message' => 'Cliente actualizado correctamente',
                'data' => $cliente
            ], 200);
        }

        return redirect('/client/listadocl');
    }

    public function destroy(Request $request, $id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json([
                'status' => false,
                'message' => 'Cliente no encontrado'
            ], 404);
        }

        if ($cliente->imagen && File::exists(public_path($cliente->imagen))) {
            File::delete(public_path($cliente->imagen));
        }

        $cliente->delete();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => true,
                'message' => 'Cliente eliminado correctamente'
            ], 200);
        }

        return redirect('/client/listadocl');
    }
}

