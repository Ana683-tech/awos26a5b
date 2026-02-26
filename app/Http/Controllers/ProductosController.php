<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\File;

class ProductosController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::query();

        if ($request->filled('buscar')) {
            $query->where('descripcion', 'LIKE', '%' . $request->buscar . '%');
        }

        $productos = $query->get();

        $productos->transform(function ($producto) {
            for ($i = 1; $i <= 3; $i++) {
                $campo = 'imagen'.$i;
                if ($producto->$campo) {
                    $producto->$campo = asset($producto->$campo);
                }
            }
            return $producto;
        });

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => true,
                'data' => $productos
            ], 200);
        }

        return view('administradores.productos', compact('productos'));
    }

    public function store(Request $request)
    {
        $data = $request->all();

        for ($i = 1; $i <= 3; $i++) {
            if ($request->hasFile('imagen'.$i)) {
                $file = $request->file('imagen'.$i);
                $nombreLimpio = preg_replace('/[^A-Za-z0-9.\-]/', '_', $file->getClientOriginalName());
                $nombreImagen = time().'_'.$i.'_'.$nombreLimpio;
                $file->move(public_path('imagenes/productos'), $nombreImagen);
                $data['imagen'.$i] = 'imagenes/productos/'.$nombreImagen;
            }
        }

        $producto = Producto::create($data);

        // RESPUESTA DINÁMICA:
        if ($request->wantsJson() || $request->is('api/*')) {
            for ($i = 1; $i <= 3; $i++) {
                $campo = 'imagen'.$i;
                if ($producto->$campo) {
                    $producto->$campo = asset($producto->$campo);
                }
            }
            return response()->json([
                'status' => true,
                'data' => $producto
            ], 201);
        }

        // Si es navegador, vuelve a la vista de productos
        return redirect()->back()->with('success', 'Producto creado correctamente');
    }

    public function show($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'status' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }

        for ($i = 1; $i <= 3; $i++) {
            $campo = 'imagen'.$i;
            if ($producto->$campo) {
                $producto->$campo = asset($producto->$campo);
            }
        }

        return response()->json([
            'status' => true,
            'data' => $producto
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['status' => false, 'message' => 'No encontrado'], 404);
            }
            return redirect()->back()->with('error', 'Producto no encontrado');
        }

        $data = $request->all();

        for ($i = 1; $i <= 3; $i++) {
            if ($request->hasFile('imagen'.$i)) {
                if ($producto->{'imagen'.$i} && File::exists(public_path($producto->{'imagen'.$i}))) {
                    File::delete(public_path($producto->{'imagen'.$i}));
                }

                $file = $request->file('imagen'.$i);
                $nombreLimpio = preg_replace('/[^A-Za-z0-9.\-]/', '_', $file->getClientOriginalName());
                $nombreImagen = time().'_'.$i.'_'.$nombreLimpio;
                $file->move(public_path('imagenes/productos'), $nombreImagen);
                $data['imagen'.$i] = 'imagenes/productos/'.$nombreImagen;
            }
        }

        $producto->update($data);

        // RESPUESTA DINÁMICA:
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => true,
                'data' => $producto
            ], 200);
        }

        return redirect()->back()->with('success', 'Producto actualizado');
    }

    public function destroy($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json(['status' => false, 'message' => 'No encontrado'], 404);
        }

        for ($i = 1; $i <= 3; $i++) {
            if ($producto->{'imagen'.$i} && File::exists(public_path($producto->{'imagen'.$i}))) {
                File::delete(public_path($producto->{'imagen'.$i}));
            }
        }

        $producto->delete();

        // RESPUESTA DINÁMICA:
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'status' => true,
                'message' => 'Producto eliminado'
            ], 200);
        }

        return redirect()->back()->with('success', 'Producto eliminado');
    }
}