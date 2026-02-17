<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pastelería | Iniciar Sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">

    <div class="bg-white p-8 rounded-xl shadow-md w-96">

        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Iniciar Sesión</h2>

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 text-sm" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        @if(session('status'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 text-sm" role="alert">
            <span class="block sm:inline">{{ session('status') }}</span>
        </div>
        @endif

        <form action="{{ route('login.validar') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Usuario</label>
                <input type="text" name="usuario" placeholder="Tu usuario"
                    class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-pink-500" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Contraseña</label>
                <input type="password" name="contrasena" placeholder="********"
                    class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-pink-500" required>
            </div>

            <button type="submit"
                class="w-full bg-pink-500 text-white py-2 rounded font-semibold hover:bg-pink-600 transition duration-200">
                Ingresar como Cliente
            </button>
        </form>

        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">O empleados vía</span>
            </div>
        </div>

        <a href="{{ url('auth/google') }}"
            class="flex items-center justify-center bg-white border border-gray-300 py-2 rounded font-medium text-gray-700 hover:bg-gray-50 transition duration-200 shadow-sm">
            <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png"
                alt="Google Logo" class="w-5 h-5 mr-2">
            Iniciar con Google
        </a>

        <p class="mt-6 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} Tu Pastelería Artesanal
        </p>

    </div>

</body>

</html>