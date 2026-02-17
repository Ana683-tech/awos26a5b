<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pastelería - @yield('titulo')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-slate-50 overflow-x-hidden font-sans antialiased">

    <header class="w-full relative z-30 shadow-md">
        <nav class="border-b border-pink-100" style="background-color: #fce7f3;">
            <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl p-4">
                <a href="{{ url('/') }}" class="flex items-center space-x-3 group">
                    <div class="overflow-hidden rounded-full border-2 border-white shadow-sm transition-transform group-hover:scale-105">
                        <img src="https://i.pinimg.com/1200x/bd/79/43/bd794330fca574dccd322ed871e25856.jpg" class="h-10 w-10 object-cover" alt="Logo" />
                    </div>
                    <span class="self-center text-2xl font-serif font-bold text-pink-700 tracking-tight">Pastelería</span>
                </a>

                <div class="flex items-center space-x-4 lg:space-x-6">
                    @if(session()->has('empleado') || session()->has('cliente') || Auth::check())
                    <div class="hidden sm:flex items-center bg-white/60 px-4 py-1.5 rounded-full border border-pink-200">
                        <span class="text-sm text-pink-800 font-semibold">
                            <i class="fas fa-user-circle mr-2"></i>
                            @if(session()->has('empleado'))
                            {{ session('empleado')->nombre }} ({{ session('empleado')->puesto }})
                            @elseif(session()->has('cliente'))
                            {{ session('cliente')->nombre }}
                            @else
                            {{ Auth::user()->name }}
                            @endif
                        </span>
                    </div>
                    <a href="{{ url('/logout') }}" class="text-sm font-bold text-rose-600 hover:text-rose-800 transition px-2">
                        <i class="fas fa-sign-out-alt mr-1"></i> Salir
                    </a>
                    @else
                    <a href="{{ url('auth/google') }}" class="flex items-center text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 border border-gray-200 px-5 py-2.5 rounded-xl transition shadow-sm">
                        <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" class="w-4 h-4 mr-2" alt="G">
                        Login con Google
                    </a>
                    @endif
                </div>
            </div>
        </nav>

        <nav class="bg-white">
            <div class="max-w-screen-xl px-4 py-1 mx-auto">
                <ul class="flex flex-row font-medium space-x-4 md:space-x-8 text-sm overflow-x-auto whitespace-nowrap scrollbar-hide">
                    <li>
                        <a href="{{ url('inicio') }}" class="block py-3 text-gray-600 hover:text-pink-600 border-b-2 border-transparent hover:border-pink-500 transition-all">
                            <i class="fas fa-home mr-1"></i> Inicio
                        </a>
                    </li>

                    {{-- VALIDACIÓN DE PUESTO PARA EL MENÚ DE ADMINISTRADORES --}}
                    @if(session()->has('empleado') && strtolower(session('empleado')->puesto) === 'gerente')
                    <li>
                        <button id="dropdownAdmins" data-dropdown-toggle="adminsDropdown" class="flex items-center py-3 text-gray-600 hover:text-pink-600 group">
                            Administradores
                            <i class="fas fa-chevron-down ml-1.5 text-[10px] group-hover:rotate-180 transition-transform"></i>
                        </button>
                        <div id="adminsDropdown" class="hidden z-40 w-48 bg-white divide-y divide-gray-100 rounded-xl shadow-xl border border-gray-100">
                            <ul class="py-2 text-sm text-gray-700">
                                <li><a href="{{ url('admins/registro') }}" class="block px-4 py-2 hover:bg-pink-50 hover:text-pink-700 transition">Nuevo Registro</a></li>
                                <li><a href="{{ url('admins/listado') }}" class="block px-4 py-2 hover:bg-pink-50 hover:text-pink-700 transition">Ver Listado</a></li>
                            </ul>
                        </div>
                    </li>
                    @endif

                    @if(session()->has('empleado'))
                    <li>
                        <button id="dropdownClientes" data-dropdown-toggle="clientesDropdown" class="flex items-center py-3 text-gray-600 hover:text-pink-600 group">
                            Clientes
                            <i class="fas fa-chevron-down ml-1.5 text-[10px] group-hover:rotate-180 transition-transform"></i>
                        </button>
                        <div id="clientesDropdown" class="hidden z-40 w-48 bg-white rounded-xl shadow-xl border border-gray-100">
                            <ul class="py-2 text-sm text-gray-700">
                                <li><a href="{{ url('client/formulario-clien') }}" class="block px-4 py-2 hover:bg-pink-50 hover:text-pink-700 transition">Registrar Nuevo</a></li>
                                <li><a href="{{ url('client/listadocl') }}" class="block px-4 py-2 hover:bg-pink-50 hover:text-pink-700 transition">Ver Listado</a></li>
                            </ul>
                        </div>
                    </li>
                    @endif

                    <li>
                        <a href="{{ url('productos') }}" class="block py-3 text-gray-600 hover:text-pink-600 border-b-2 border-transparent hover:border-pink-500 transition-all">
                            Productos
                        </a>
                    </li>

                    <li>
                        <button id="dropdownPedidos" data-dropdown-toggle="pedidosDropdown" class="flex items-center py-3 text-gray-600 hover:text-pink-600 group">
                            Pedidos
                            <i class="fas fa-chevron-down ml-1.5 text-[10px] group-hover:rotate-180 transition-transform"></i>
                        </button>
                        <div id="pedidosDropdown" class="hidden z-40 w-48 bg-white rounded-xl shadow-xl border border-gray-100">
                            <ul class="py-2 text-sm text-gray-700">
                                <li><a href="{{ url('pedido/formulario-pedido') }}" class="block px-4 py-2 hover:bg-pink-50 hover:text-pink-700 transition">Crear Pedido</a></li>
                                @if(session()->has('empleado'))
                                <li><a href="{{ url('pedidos/listado') }}" class="block px-4 py-2 hover:bg-pink-50 hover:text-pink-700 transition">Gestión de Ordenes</a></li>
                                @endif
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="max-w-screen-xl mx-auto px-4 py-10">
        <div class="bg-white rounded-3xl p-6 md:p-10 shadow-sm border border-gray-100 min-h-[70vh]">
            @yield('contenido')
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>

</html>