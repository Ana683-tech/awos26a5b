<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class ProductosController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::query();

        if ($request->filled('buscar')) {
            $query->where('descripcion', 'LIKE', '%' . $request->buscar . '%');
        }

        $productos = $query->get();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => true,
                'data' => $productos
            ], 200);
        }

        return view('administradores.productos', compact('productos'));
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

        return response()->json([
            'status' => true,
            'data' => $producto
        ], 200);
    }

    public function store(Request $request)
    {
        if ($request->wantsJson() || $request->is('api/*')) {

            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:255',
                'descripcion' => 'required|string',
                'precio' => 'required|numeric',
                'stock' => 'required|integer',
                'imagen1' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'imagen2' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'imagen3' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
        }

        $producto = new Producto();
        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->precio = $request->precio;
        $producto->stock = $request->stock;

        for ($i = 1; $i <= 3; $i++) {
            $campo = 'imagen'.$i;

            if ($request->hasFile($campo)) {

                if ($producto->$campo && File::exists(public_path($producto->$campo))) {
                    File::delete(public_path($producto->$campo));
                }

                $imagen = $request->file($campo);
                $nombreImagen = time()."_img{$i}_".$imagen->getClientOriginalName();
                $imagen->move(public_path('imagenes/productos'), $nombreImagen);
                $producto->$campo = 'imagenes/productos/'.$nombreImagen;
            }
        }

        $producto->save();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => true,
                'message' => 'Producto creado correctamente',
                'data' => $producto
            ], 201);
        }

        return redirect('/produc/productos');
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        if ($request->wantsJson() || $request->is('api/*')) {

            $validator = Validator::make($request->all(), [
                'precio' => 'nullable|numeric',
                'stock' => 'nullable|integer',
                'imagen1' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'imagen2' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'imagen3' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
        }

        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->precio = $request->precio;
        $producto->stock = $request->stock;

        for ($i = 1; $i <= 3; $i++) {
            $campo = 'imagen'.$i;

            if ($request->hasFile($campo)) {

                if ($producto->$campo && File::exists(public_path($producto->$campo))) {
                    File::delete(public_path($producto->$campo));
                }

                $imagen = $request->file($campo);
                $nombreImagen = time()."_img{$i}_".$imagen->getClientOriginalName();
                $imagen->move(public_path('imagenes/productos'), $nombreImagen);
                $producto->$campo = 'imagenes/productos/'.$nombreImagen;
            }
        }

        $producto->save();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => true,
                'message' => 'Producto actualizado correctamente',
                'data' => $producto
            ], 200);
        }

        return redirect('/produc/productos');
    }

    public function destroy(Request $request, $id)
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

            if ($producto->$campo && File::exists(public_path($producto->$campo))) {
                File::delete(public_path($producto->$campo));
            }
        }

        $producto->delete();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => true,
                'message' => 'Producto eliminado correctamente'
            ], 200);
        }

        return redirect('/produc/productos');
    }
}
