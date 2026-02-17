@extends('/layouts/app')

@section('titulo', 'crear pedido')

@section('contenido')
<div class="max-w-screen-xl mx-auto mt-40 px-4">

  
    <div class="bg-white p-6 rounded-lg shadow mb-8">
        <h2 class="text-2xl font-bold mb-6">Registrar Pedido</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">

          
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Nombre del cliente
                    </label>
                    <input type="text"
                           class="w-full border rounded px-4 py-2"
                           placeholder="Ej. Juan Pérez">
                </div>

                <button
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 w-full md:w-auto">
                    Finalizar pedido
                </button>
            </div>

          
            <div class="text-right pt-2">
                <span class="text-lg font-semibold">
                    Productos agregados:
                    <span id="contador" class="text-blue-600">0</span>
                </span>
            </div>
        </div>
    </div>

    
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-6">Productos</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

            
            <div class="bg-gray-50 p-4 rounded shadow">
                <h3 class="font-semibold">Pastel de Chocolate</h3>
                <p class="text-gray-600 mb-3">$280</p>
                <button onclick="agregarProducto()"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Agregar
                </button>
            </div>

            <div class="bg-gray-50 p-4 rounded shadow">
                <h3 class="font-semibold">Pastel 3 Leches</h3>
                <p class="text-gray-600 mb-3">$300</p>
                <button onclick="agregarProducto()"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Agregar
                </button>
            </div>

            <div class="bg-gray-50 p-4 rounded shadow">
                <h3 class="font-semibold">Pastel de Vainilla</h3>
                <p class="text-gray-600 mb-3">$250</p>
                <button onclick="agregarProducto()"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Agregar
                </button>
            </div>

            <div class="bg-gray-50 p-4 rounded shadow">
                <h3 class="font-semibold">Gelatina de Fresa</h3>
                <p class="text-gray-600 mb-3">$60</p>
                <button onclick="agregarProducto()"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Agregar
                </button>
            </div>

            <div class="bg-gray-50 p-4 rounded shadow">
                <h3 class="font-semibold">Flan Napolitano</h3>
                <p class="text-gray-600 mb-3">$90</p>
                <button onclick="agregarProducto()"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Agregar
                </button>
            </div>

            <div class="bg-gray-50 p-4 rounded shadow">
                <h3 class="font-semibold">Chocolate Artesanal</h3>
                <p class="text-gray-600 mb-3">$120</p>
                <button onclick="agregarProducto()"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Agregar
                </button>
            </div>

            <div class="bg-gray-50 p-4 rounded shadow">
                <h3 class="font-semibold">Gelatina de Limón</h3>
                <p class="text-gray-600 mb-3">$55</p>
                <button onclick="agregarProducto()"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Agregar
                </button>
            </div>

        </div>
    </div>
</div>

<script>
let contador = 0;

function agregarProducto() {
    contador++;
    document.getElementById('contador').textContent = contador;
}
</script>
@endsection
