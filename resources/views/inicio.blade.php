@extends('layouts.app')

@section('titulo', 'Inicio')

@section('contenido')
<div class="flex flex-col items-center justify-center py-12 px-4">
    
    <div class="text-center max-w-2xl w-full">
        {{-- Mensajes de error por falta de permisos (Middleware) --}}
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow-md" role="alert">
                <p class="font-bold">Acceso restringido</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <h1 class="text-4xl font-extrabold text-gray-800 mb-2">Pastelería Artesanal</h1>
        
        {{-- Saludo personalizado usando la sesión --}}
        @if(session()->has('empleado'))
            <p class="text-xl text-pink-600 font-semibold mb-8">
                Bienvenido, {{ session('empleado')->nombre }} 
                <span class="text-gray-500 text-sm font-normal">({{ session('empleado')->puesto }})</span>
            </p>
        @endif

        {{-- Panel de acciones rápidas --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            
            {{-- Opción para TODOS los empleados --}}
            <a href="/produc/productos" class="p-6 bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition">
                <h3 class="font-bold text-gray-700 text-lg">📦 Inventario</h3>
                <p class="text-gray-500 text-sm">Gestionar productos y pasteles</p>
            </a>

            <a href="/client/listadocl" class="p-6 bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition">
                <h3 class="font-bold text-gray-700 text-lg">👥 Clientes</h3>
                <p class="text-gray-500 text-sm">Ver y registrar clientes</p>
            </a>

            {{-- OPCIONES EXCLUSIVAS PARA EL GERENTE --}}
            @if(session('empleado') && strtolower(session('empleado')->puesto) === 'gerente')
                <div class="md:col-span-2 border-t pt-6 mt-2">
                    <p class="text-left text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Panel de Administración</p>
                </div>

                <a href="/admins/listado" class="p-6 bg-pink-50 border border-pink-100 rounded-xl shadow-sm hover:bg-pink-100 transition">
                    <h3 class="font-bold text-pink-700 text-lg">⚙️ Gestionar Personal</h3>
                    <p class="text-pink-600 text-sm">Ver, editar y eliminar empleados</p>
                </a>

                <a href="/admins/registro" class="p-6 bg-gray-800 rounded-xl shadow-sm hover:bg-gray-900 transition">
                    <h3 class="font-bold text-white text-lg">➕ Nuevo Administrador</h3>
                    <p class="text-gray-300 text-sm">Dar de alta personal en el sistema</p>
                </a>
            @endif

        </div>

        <div class="mt-12">
            <a href="{{ route('logout') }}" class="text-gray-400 hover:text-red-500 text-sm underline">
                Cerrar sesión de forma segura
            </a>
        </div>
    </div>
</div>
@endsection