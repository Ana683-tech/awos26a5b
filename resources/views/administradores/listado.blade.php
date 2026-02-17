    @extends('layouts/app')

    @section('titulo', 'mostrar administrador')

    @section('contenido')
    <section class="bg-white dark:bg-gray-900 mt-25">
        <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 lg:px-6">

            <div class="mx-auto mb-8 max-w-screen-sm lg:mb-16">
                <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">
                    Administradores
                </h2>
                <p class="font-light text-gray-500 sm:text-xl dark:text-gray-400">
                    Lista de Empleados
                </p>
            </div>

            <div class="grid gap-8 lg:gap-16 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">

                @forelse ($administradores as $admin)
                <div class="text-center text-gray-500 dark:text-gray-400">

                    {{-- MOSTRAR IMAGEN SOLO SI EXISTE --}}
                    @if($admin->imagen)
                    <img class="mx-auto mb-4 w-36 h-36 rounded-full object-cover"
                        src="{{ asset($admin->imagen) }}">
                    @endif

                    <h3 class="mb-1 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        {{ $admin->nombre }}
                    </h3>

                    <a href="{{ route('empleados.edit', $admin->id_empleado) }}"
                        class="text-sm text-blue-600 hover:underline">
                        Editar perfil
                    </a>

                    <p class="mt-2 font-semibold
                            {{ $admin->estado == 1 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $admin->estado == 1 ? 'Activo' : 'Inactivo' }}
                    </p>

                    <p class="mt-1 mb-4">
                        {{ $admin->puesto }}
                    </p>

                </div>
                @empty
                <p class="text-gray-500 col-span-4">
                    No hay empleados registrados
                </p>
                @endforelse

            </div>
        </div>
    </section>
    @endsection