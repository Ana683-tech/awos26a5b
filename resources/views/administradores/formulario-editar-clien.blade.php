@extends('layouts.app')

@section('titulo','editar cliente')

@section('contenido')
<section class="bg-white dark:bg-gray-900 mt-24">
  <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">

      <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">
          Actualizar cliente
      </h2>

      {{-- IMPORTANTE: enctype="multipart/form-data" para permitir subir archivos --}}
      <form action="{{ route('clientes.update', $cliente->id_cliente) }}" 
            method="POST" 
            enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

              <div class="sm:col-span-2">
                  <label class="block mb-2 text-sm font-medium">Nombre</label>
                  <input type="text" name="nombre" value="{{ $cliente->nombre }}"
                         class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white" required>
              </div>

              <div class="sm:col-span-2">
                  <label class="block mb-2 text-sm font-medium">Apellidos</label>
                  <input type="text" name="apellidos" value="{{ $cliente->apellidos }}"
                         class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white">
              </div>

              <div class="w-full">
                  <label class="block mb-2 text-sm font-medium">Teléfono</label>
                  <input type="text" name="telefono" value="{{ $cliente->telefono }}"
                         class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white">
              </div>

              <div class="w-full">
                  <label class="block mb-2 text-sm font-medium">Email</label>
                  <input type="email" name="email" value="{{ $cliente->email }}"
                         class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white">
              </div>

              <div class="sm:col-span-2">
                  <label class="block mb-2 text-sm font-medium">Usuario</label>
                  <input type="text" name="usuario" value="{{ $cliente->usuario }}"
                         class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white">
              </div>

              <div class="sm:col-span-2">
                  <label class="block mb-2 text-sm font-medium">
                      Nueva contraseña (dejar en blanco para mantener la actual)
                  </label>
                  <input type="password" name="contrasena"
                         class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white">
              </div>

              {{-- SECCIÓN PARA CAMBIAR LA FOTO --}}
              <div class="sm:col-span-2">
                  <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                      Cambiar fotografía
                  </label>
                  <input type="file" name="imagen" accept="image/*"
                         class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                  <p class="mt-1 text-xs text-gray-500">PNG, JPG o JPEG (Recomendado: cuadrada).</p>
              </div>

              {{-- PREVISUALIZACIÓN DE FOTO ACTUAL --}}
              @if($cliente->imagen)
              <div class="sm:col-span-2">
                  <label class="block mb-2 text-sm font-medium">Foto actual</label>
                  <img src="{{ asset($cliente->imagen) }}" 
                       class="w-32 h-32 rounded-full object-cover border-2 border-gray-200">
              </div>
              @endif

              <div class="sm:col-span-2 text-right mt-4">
                  <a href="/client/listadocl" class="mr-4 text-gray-500 hover:underline">Cancelar</a>
                  <button type="submit"
                          class="px-6 py-2 bg-green-600 text-white font-bold rounded hover:bg-green-700 transition">
                      Guardar Cambios
                  </button>
              </div>

          </div>
      </form>

  </div>
</section>
@endsection 