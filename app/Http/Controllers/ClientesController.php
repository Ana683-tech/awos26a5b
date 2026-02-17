<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;


class ClientesController extends Controller
{
    public function index()
    {
        $clientes = Cliente::all();
        return view('administradores.listadocl', compact('clientes'));
    }

    public function create()
    {
        return view('administradores.formulario-crear-clien');
    }

    public function store(Request $request)
    {
        $cliente = new Cliente();
        // Usamos los nombres que vienen del formulario de creación
        $cliente->nombre    = $request->nombres; 
        $cliente->apellidos = $request->apellidos;
        $cliente->telefono  = $request->telefono;
        $cliente->email     = $request->correo;
        $cliente->usuario   = $request->usuario;
        $cliente->contrasena = Hash::make($request->contrasena);

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = time().'_'.$imagen->getClientOriginalName();
            
          
            $imagen->move(public_path('imagenes/clientes'), $nombreImagen);
            
            $cliente->imagen = 'imagenes/clientes/'.$nombreImagen;
        }

        $cliente->save();
        return redirect('/client/listadocl');
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('administradores.formulario-editar-clien', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);
        
       
        $cliente->nombre    = $request->nombre; 
        $cliente->apellidos = $request->apellidos;
        $cliente->telefono  = $request->telefono;
        $cliente->email     = $request->email;
        $cliente->usuario   = $request->usuario;

        if ($request->filled('contrasena')) {
            $cliente->contrasena = Hash::make($request->contrasena);
        }

        if ($request->hasFile('imagen')) {
            // 1. Borrar imagen anterior si existe físicamente
            if ($cliente->imagen && File::exists(public_path($cliente->imagen))) {
                File::delete(public_path($cliente->imagen));
            }
            
            // 2. Procesar la nueva imagen
            $imagen = $request->file('imagen');
            $nombreImagen = time().'_'.$imagen->getClientOriginalName();
            $imagen->move(public_path('imagenes/clientes'), $nombreImagen);
            
            // 3. Guardar la nueva ruta
            $cliente->imagen = 'imagenes/clientes/'.$nombreImagen;
        }

        $cliente->save();
        return redirect('/client/listadocl');
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        
        // Borramos la imagen del servidor antes de eliminar el registro
        if ($cliente->imagen && File::exists(public_path($cliente->imagen))) {
            File::delete(public_path($cliente->imagen));
        }
        
        $cliente->delete();
        return redirect('/client/listadocl');
    }
}