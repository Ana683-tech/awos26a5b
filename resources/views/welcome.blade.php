<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pastelería | Inicio</title>

    <link href="https://fonts.bunny.net/css?family=inter:400,500,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .soft-bg {
            background-color: #fcfcfc;
        }
    </style>
</head>

<body class="antialiased soft-bg text-gray-800">

    <nav class="flex justify-between items-center p-8 max-w-7xl mx-auto">
    <div class="text-2xl font-bold tracking-tight text-pink-500">PASTELERIA</div>
    <div class="space-x-6 text-sm font-medium flex items-center">
        <a href="/productos" class="hover:text-pink-500 transition">Productos</a>
        
        @if(session()->has('cliente'))
            <span class="text-gray-500 italic">Hola, {{ session('cliente')->nombre }}</span>
            <a href="{{ route('logout') }}" class="text-red-400 hover:text-red-600 font-bold">Cerrar Sesión</a>
        @else
            <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-900">Entrar</a>
        @endif
    </div>
</nav>

<main class="max-w-4xl mx-auto px-6 py-12">
    <header class="text-center mb-16">
        <h1 class="text-4xl font-light mb-4">
            @if(session()->has('cliente'))
                ¡Qué gusto verte de nuevo, <span class="font-bold text-gray-900">{{ session('cliente')->nombre }}</span>!
            @else
                Repostería artesanal <br><span class="font-bold text-gray-900">hecha con cuidado.</span>
            @endif
        </h1>
        <p class="text-gray-500">Bienvenido a tu rincón dulce favorito.</p>
    </header>
    </main>

        <div class="grid grid-cols-1 gap-6">

            <a href="/productos"
                class="flex items-center justify-between p-6 bg-white border border-gray-100 rounded-xl hover:border-pink-200 hover:bg-pink-50/30 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <span class="text-3xl"></span>
                    <div>
                        <h3 class="font-semibold text-lg text-gray-900">Catálogo de Pasteles</h3>
                        <p class="text-gray-500 text-sm">Mira lo que tenemos horneado hoy</p>
                    </div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>

            <div class="p-8 bg-white border border-gray-100 rounded-xl shadow-sm">
                <div class="flex items-center gap-4 mb-6">
                    <span class="text-3xl"></span>
                    <div>
                        <h3 class="font-semibold text-lg text-gray-900">Servicio a domicilio</h3>
                        <p class="text-gray-500 text-sm">Verifica si llegamos a tu zona</p>
                    </div>
                </div>

                <div class="flex flex-col items-center">
                    <button onclick="getGeolocation()"
                        class="w-full sm:w-auto px-10 py-3 bg-gray-900 text-white text-sm font-bold rounded-lg hover:bg-gray-800 transition active:scale-95">
                        Detectar mi dirección exacta
                    </button>
                    <p id="resultado-ubicacion" class="mt-4 text-sm text-pink-600 font-medium hidden text-center"></p>
                </div>
            </div>

        </div>


    </main>
    <!-- funcion en la cual le decimos a laravel ahora que busque nuestra geolocalizacion -->
    <script>
        function getGeolocation() {
            const display = document.getElementById('resultado-ubicacion');
            display.classList.remove('hidden');
            display.innerHTML = "Localizando...";

            if (!navigator.geolocation) {
                display.innerHTML = "Navegador no compatible.";
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    fetch('/obtener-ubicacion', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                lat: position.coords.latitude,
                                lng: position.coords.longitude
                            })
                        })
                     
                        .then(response => {
                            if (!response.ok) {
                             
                                throw new Error("Código de error: " + response.status);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.direccion) {
                                display.innerHTML = "📍 <strong>Dirección:</strong> " + data.direccion;
                                display.classList.replace('text-pink-600', 'text-green-600');
                            } else {
                                display.innerHTML = "❌ " + (data.error || "No encontrada.");
                            }
                        })
                        .catch((error) => {
                            console.error("Detalle:", error);
                            display.innerHTML = "Error: " + error.message;
                        });
                },
                () => {
                    display.innerHTML = "Permiso de ubicación denegado.";
                }
            );
        }
    </script>
</body>

</html>
