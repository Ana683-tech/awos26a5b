<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\Http; 
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
        return view('administradores.productos', compact('productos'));
    }

    public function store(Request $request)
    {
        $producto = new Producto();
        $producto->nombre      = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->precio      = $request->precio;
        $producto->stock       = $request->stock;

        // Procesar las 3 imágenes
        for ($i = 1; $i <= 3; $i++) {
            $campo = 'imagen' . $i;
            if ($request->hasFile($campo)) {
                $imagen = $request->file($campo);
                $nombreImagen = time() . "_img{$i}_" . $imagen->getClientOriginalName();
                $imagen->move(public_path('imagenes/productos'), $nombreImagen);
                $producto->$campo = 'imagenes/productos/' . $nombreImagen;
            }
        }

        $producto->save();
        return redirect('/produc/productos');
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $producto->nombre      = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->precio      = $request->precio;
        $producto->stock       = $request->stock;

        for ($i = 1; $i <= 3; $i++) {
            $campo = 'imagen' . $i;
            if ($request->hasFile($campo)) {
                // Borrar la anterior si existe
                if ($producto->$campo && File::exists(public_path($producto->$campo))) {
                    File::delete(public_path($producto->$campo));
                }
                
                $imagen = $request->file($campo);
                $nombreImagen = time() . "_img{$i}_" . $imagen->getClientOriginalName();
                $imagen->move(public_path('imagenes/productos'), $nombreImagen);
                $producto->$campo = 'imagenes/productos/' . $nombreImagen;
            }
        }

        $producto->save();
        return redirect('/produc/productos');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        // Borrar las 3 imágenes físicas
        for ($i = 1; $i <= 3; $i++) {
            $campo = 'imagen' . $i;
            if ($producto->$campo && File::exists(public_path($producto->$campo))) {
                File::delete(public_path($producto->$campo));
            }
        }
        $producto->delete();
        return redirect('/produc/productos');
    }
    
    // nuieva funcion para obtener ubicacion a partir de coordenadas
    
public function obtenerUbicacion(Request $request)
{
    $lat = $request->input('lat');
    $lng = $request->input('lng');
    
    $apiKey = env('OPENCAGE_API_KEY'); 
    
    if (!$lat || !$lng) {
        return response()->json(['error' => 'Coordenadas faltantes'], 400);
    }
    
    
    $response = Http::withOptions([
        'verify' => false,
    ])->get("https://api.opencagedata.com/geocode/v1/json", [
        'q' => "$lat,$lng",
        'key' => $apiKey,
        'language' => 'es',
        'no_annotations' => 1
    ]);

    if ($response->successful()) {
        $data = $response->json();
        if (!empty($data['results'])) {
            $direccion = $data['results'][0]['formatted'];
            return response()->json(['direccion' => $direccion]);
        }
    }

    return response()->json(['error' => 'No se pudo obtener la dirección de la API'], 500);
}
}