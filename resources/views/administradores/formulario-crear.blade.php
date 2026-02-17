@extends('/layouts/app')

@section('titulo','crear administrador')

@section('contenido')
<section class="bg-white dark:bg-gray-900 mt-24">
    <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">

        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">
            Crear administrador
        </h2>

        <form action="/admins/store" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                <div class="sm:col-span-2">
                    <label class="block mb-2 text-sm font-medium">
                        Nombre
                    </label>
                    <input type="text" name="nombre" required
                        class="w-full p-2 border rounded focus:ring-pink-500 focus:border-pink-500"
                        placeholder="Ingrese nombre">
                </div>

                <div class="sm:col-span-2">
                    <label class="block mb-2 text-sm font-medium">
                        Apellido
                    </label>
                    <input type="text" name="apellido" required
                        class="w-full p-2 border rounded focus:ring-pink-500 focus:border-pink-500"
                        placeholder="Ingrese apellido">
                </div>

                <div class="w-full">
                    <label class="block mb-2 text-sm font-medium">
                        Salario
                    </label>
                    <input type="number" name="salario" required step="0.01"
                        class="w-full p-2 border rounded focus:ring-pink-500 focus:border-pink-500"
                        placeholder="$0.00">
                </div>

                <div class="w-full">
                    <label class="block mb-2 text-sm font-medium">
                        Usuario
                    </label>
                    <input type="text" name="usuario" required
                        class="w-full p-2 border rounded focus:ring-pink-500 focus:border-pink-500"
                        placeholder="Usuario">
                </div>

                {{-- CAMPO DE CORREO AGREGADO --}}
                <div class="sm:col-span-2">
                    <label class="block mb-2 text-sm font-medium">
                        Correo Electrónico (Institucional)
                    </label>
                    <input type="email" name="email" required
                        class="w-full p-2 border rounded focus:ring-pink-500 focus:border-pink-500"
                        placeholder="ejemplo@soy.utj.edu.mx">
                </div>

                <div class="sm:col-span-2">
                    <label class="block mb-2 text-sm font-medium">
                        Puesto
                    </label>
                    <input type="text" name="puesto" required
                        class="w-full p-2 border rounded focus:ring-pink-500 focus:border-pink-500"
                        placeholder="Ej. Administrador, Cajero">
                </div>

                <div class="sm:col-span-2">
                    <label class="block mb-2 text-sm font-medium">
                        Contraseña
                    </label>
                    <input type="password" name="contrasena" required
                        class="w-full p-2 border rounded focus:ring-pink-500 focus:border-pink-500"
                        placeholder="Contraseña">
                </div>

                {{-- SUBIR IMAGEN --}}
                <div class="sm:col-span-2">
                    <label class="block mb-2 text-sm font-medium">
                        Foto del administrador
                    </label>

                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col w-full h-32 border-2 border-dashed rounded-lg cursor-pointer hover:bg-gray-50">
                            <div class="flex flex-col items-center justify-center pt-7">
                                <p class="text-sm text-gray-500">
                                    Arrastra una imagen aquí o haz clic para seleccionar
                                </p>
                                <p class="text-xs text-gray-400">
                                    PNG o JPG
                                </p>
                            </div>
                            <input type="file" name="imagen" class="hidden"
                                accept="image/png, image/jpeg">
                        </label>
                    </div>
                </div>

                <div class="sm:col-span-2 flex justify-end mt-4">
                    <button type="submit"
                        style="background-color: #fce7f3;"
                        class="px-6 py-2 text-sm font-bold text-pink-700 rounded-lg hover:bg-pink-200 transition-colors">
                        Guardar
                    </button>
                </div>

            </div>
        </form>

    </div>
</section>
@endsection