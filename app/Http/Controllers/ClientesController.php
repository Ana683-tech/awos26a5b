<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\File;

class ClientesController extends Controller
{
    public function index(Request $request)
    {
        $clientes = Cliente::all();

        $clientes->transform(function ($cliente) {
            if ($cliente->imagen) {
                $cliente->imagen = asset($cliente->imagen);
            }
            return $cliente;
        });

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => true,
                'data' => $clientes
            ], 200);
        }

        return view('administradores.listadocl', compact('clientes'));
    }

    // Método para cargar la vista de edición
    public function edit($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return redirect()->back()->with('error', 'Cliente no encontrado');
        }

        return view('administradores.formulario-editar-clien', compact('cliente'));
    }

    public function store(Request $request)
    {
        $data = $request->all();

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombreLimpio = preg_replace('/[^A-Za-z0-9.\-]/', '_', $file->getClientOriginalName());
            $nombreImagen = time().'_'.$nombreLimpio;
            $file->move(public_path('imagenes/clientes'), $nombreImagen);
            $data['imagen'] = 'imagenes/clientes/'.$nombreImagen;
        }

        $cliente = Cliente::create($data);

        if ($request->wantsJson() || $request->is('api/*')) {
            if ($cliente->imagen) {
                $cliente->imagen = asset($cliente->imagen);
            }
            return response()->json([
                'status' => true,
                'data' => $cliente
            ], 201);
        }

        return redirect('/client/listadocl')->with('success', 'Cliente registrado');
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

        if ($cliente->imagen) {
            $cliente->imagen = asset($cliente->imagen);
        }

        return response()->json([
            'status' => true,
            'data' => $cliente
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['status' => false, 'message' => 'No encontrado'], 404);
            }
            return redirect()->back()->with('error', 'Cliente no encontrado');
        }

        $data = $request->all();

        if ($request->hasFile('imagen')) {
            if ($cliente->imagen && File::exists(public_path($cliente->imagen))) {
                File::delete(public_path($cliente->imagen));
            }

            $file = $request->file('imagen');
            $nombreLimpio = preg_replace('/[^A-Za-z0-9.\-]/', '_', $file->getClientOriginalName());
            $nombreImagen = time().'_'.$nombreLimpio;
            $file->move(public_path('imagenes/clientes'), $nombreImagen);
            $data['imagen'] = 'imagenes/clientes/'.$nombreImagen;
        }

        $cliente->update($data);

        if ($request->wantsJson() || $request->is('api/*')) {
            if ($cliente->imagen) {
                $cliente->imagen = asset($cliente->imagen);
            }
            return response()->json([
                'status' => true,
                'data' => $cliente
            ], 200);
        }

        return redirect('/client/listadocl')->with('success', 'Cliente actualizado');
    }

    // ARREGLADO: Se añadió (Request $request) para que la variable exista
    public function destroy(Request $request, $id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['status' => false, 'message' => 'No encontrado'], 404);
            }
            return redirect()->back()->with('error', 'Cliente no encontrado');
        }

        if ($cliente->imagen && File::exists(public_path($cliente->imagen))) {
            File::delete(public_path($cliente->imagen));
        }

        $cliente->delete();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => true,
                'message' => 'Cliente eliminado'
            ], 200);
        }

        return redirect()->back()->with('success', 'Cliente eliminado');
    }
}