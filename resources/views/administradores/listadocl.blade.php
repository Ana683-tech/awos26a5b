@extends('layouts.app')

@section('titulo', 'mostrar clientes')

@section('contenido')
<section class="bg-white dark:bg-gray-900 mt-25">
    <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 lg:px-6">
        <div class="mx-auto mb-8 max-w-screen-sm lg:mb-16">
            <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Clientes</h2>
            <p class="font-light text-gray-500 sm:text-xl dark:text-gray-400">Lista de clientes registrados</p>
        </div>

        <div class="grid gap-8 lg:gap-16 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            @forelse ($clientes as $cliente)
                {{-- Se quitaron las clases border, p-4, rounded-lg y shadow-sm --}}
                <div class="text-center text-gray-500 dark:text-gray-400">
                    @if($cliente->imagen)
                        <img class="mx-auto mb-4 w-36 h-36 rounded-full object-cover" src="{{ asset($cliente->imagen) }}">
                    @else
                        <img class="mx-auto mb-4 w-36 h-36 rounded-full" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/bonnie-green.png">
                    @endif

                    <h3 class="mb-1 text-xl font-bold text-gray-900 dark:text-white">{{ $cliente->nombre }}</h3>
                    <p>{{ $cliente->telefono }}</p>
                    <p class="mb-4 text-sm">{{ $cliente->email }}</p>

                    <div class="flex justify-center gap-2">
                        <a href="{{ route('clientes.edit', $cliente->id_cliente) }}" class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">Editar</a>
                        <form action="{{ route('clientes.destroy', $cliente->id_cliente) }}" method="POST" onsubmit="return confirm('¿Borrar cliente?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-4 py-2 text-sm bg-red-600 text-white rounded hover:bg-red-700">Borrar</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="col-span-4 text-gray-500">No hay clientes registrados</p>
            @endforelse
        </div>
    </div>
</section>
@endsection