@extends('layouts.app')

@section('titulo', 'Crear Cliente')

@section('contenido')
<section class="bg-white dark:bg-gray-900">
    <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
        
       <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">
            Crear Cliente
        </h2>

        <form action="{{ route('clientes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                <div class="sm:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900">
                        Nombre(s)
                    </label>
                    <input type="text" name="nombres" required
                        class="w-full p-2.5 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-pink-500 focus:border-pink-500"
                        placeholder="Ingrese nombre(s)">
                </div>

                <div class="sm:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900">
                        Apellido(s)
                    </label>
                    <input type="text" name="apellidos" required
                        class="w-full p-2.5 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-pink-500 focus:border-pink-500"
                        placeholder="Ingrese apellido(s)">
                </div>

                <div class="w-full">
                    <label class="block mb-2 text-sm font-medium text-gray-900">
                        Teléfono
                    </label>
                    <input type="text" name="telefono" required
                        class="w-full p-2.5 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-pink-500 focus:border-pink-500"
                        placeholder="5512345678">
                </div>

                <div class="w-full">
                    <label class="block mb-2 text-sm font-medium text-gray-869">
                        Correo
                    </label>
                    <input type="email" name="correo" required
                        class="w-full p-2.5 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-pink-500 focus:border-pink-500"
                        placeholder="correo@ejemplo.com">
                </div>

                <div class="w-full">
                    <label class="block mb-2 text-sm font-medium text-gray-900">
                        Usuario
                    </label>
                    <input type="text" name="usuario" required
                        class="w-full p-2.5 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-pink-500 focus:border-pink-500"
                        placeholder="Nombre de usuario">
                </div>

                <div class="w-full">
                    <label class="block mb-2 text-sm font-medium text-gray-900">
                        Contraseña
                    </label>
                    <input type="password" name="contrasena" required
                        class="w-full p-2.5 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-pink-500 focus:border-pink-500"
                        placeholder="••••••••">
                </div>

                {{-- AREA DE SUBIDA DE IMAGEN (Estilo Admin) --}}
                <div class="sm:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900">
                        Foto del cliente
                    </label>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-pink-50 hover:border-pink-300 transition-all">
                            <div class="flex flex-col items-center justify-center pt-7">
                                <p class="text-sm text-gray-500">
                                    Arrastra una imagen aquí o haz clic para seleccionar
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    Formatos permitidos: PNG o JPG
                                </p>
                            </div>
                            <input type="file" name="imagen" class="hidden" accept="image/*">
                        </label>
                    </div>
                </div>

                {{-- BOTÓN ROSA --}}
                <div class="sm:col-span-2 flex justify-end mt-4">
                    <button type="submit"
                        style="background-color: #fce7f3;"
                        class="px-8 py-2.5 text-sm font-bold text-pink-700 rounded-lg hover:bg-pink-200 transition-colors shadow-sm">
                        Guardar Cliente
                    </button>
                </div>

            </div>
      
    </div>
</section>
@endsection