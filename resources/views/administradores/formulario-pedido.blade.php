@extends('/layouts/app')

@section('titulo', 'Crear Pedido')

@section('contenido')
<div class="max-w-screen-xl mx-auto mt-40 px-4">
    <div class="bg-white p-6 rounded-lg shadow mb-8">
        <h2 class="text-2xl font-bold mb-6">Registrar Pedido Nuevo</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Seleccionar Cliente</label>
                    <select id="id_cliente" class="w-full border rounded px-4 py-2">
                        <option value="">-- Seleccione un cliente --</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id_cliente }}">{{ $cliente->nombre }} {{ $cliente->apellidos }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Número de Pedido (ID)</label>
                    <input type="number" id="id_pedido" class="w-full border rounded px-4 py-2" placeholder="Ej. 101">
                </div>

                <div class="text-xl font-bold text-gray-800">
                    Total a Pagar: $<span id="total_acumulado">0.00</span>
                </div>

                <button onclick="finalizarPedido()"
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 w-full md:w-auto">
                    Guardar Pedido en API
                </button>
            </div>

            <div class="text-right pt-2">
                <span class="text-lg font-semibold">
                    Productos en el carrito:
                    <span id="contador" class="text-blue-600">0</span>
                </span>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-6">Catálogo de Productos Disponibles</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($productos as $producto)
            <div class="bg-gray-50 p-4 rounded shadow border border-gray-200">
                <img src="{{ asset($producto->imagen1) }}" class="w-full h-32 object-cover rounded mb-2" onerror="this.src='https://via.placeholder.com/150'">
                
                <h3 class="font-semibold">{{ $producto->descripcion }}</h3>
                <p class="text-green-600 font-bold mb-3">${{ number_format($producto->precio, 2) }}</p>
                
                <button onclick="agregarAlCarrito({{ $producto->precio }})"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 w-full">
                    Agregar al Pedido
                </button>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let contador = 0;
    let totalGlobal = 0;

    function agregarAlCarrito(precio) {
        contador++;
        totalGlobal += parseFloat(precio);
        
        document.getElementById('contador').textContent = contador;
        document.getElementById('total_acumulado').textContent = totalGlobal.toFixed(2);
    }

    async function finalizarPedido() {
        const id_cliente = document.getElementById('id_cliente').value;
        const id_pedido = document.getElementById('id_pedido').value;
        const total = totalGlobal;
        const fecha = new Date().toISOString().slice(0, 10); // Formato YYYY-MM-DD

        if (!id_cliente || !id_pedido || total === 0) {
            alert("Por favor completa los datos y agrega productos");
            return;
        }

        // Datos para la API
        const datosPedido = {
            id_pedido: id_pedido,
            id_cliente: id_cliente,
            fecha_pedido: fecha,
            total: total
        };

        // --- CORRECCIÓN AQUÍ: Recuperar el token del almacenamiento local ---
        // ESTO ES LO QUE HACE QUE FUNCIONE DESDE LA WEB
        const token = localStorage.getItem('access_token'); 

        try {
            const response = await fetch('/api/pedidos', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    // --- CORRECCIÓN AQUÍ: Enviar el token Bearer ---
                    'Authorization': `Bearer ${token}` 
                },
                body: JSON.stringify(datosPedido)
            });

            const result = await response.json();

            if (response.ok && result.status) {
                Swal.fire('¡Éxito!', 'Pedido registrado en la base de datos', 'success');
                // Opcional: limpiar formulario o redireccionar
                // document.getElementById('id_pedido').value = '';
                // contador = 0;
                // totalGlobal = 0;
                // document.getElementById('contador').textContent = contador;
                // document.getElementById('total_acumulado').textContent = totalGlobal.toFixed(2);
            } else {
                Swal.fire('Error', 'No se pudo guardar: ' + JSON.stringify(result.message), 'error');
            }
        } catch (error) {
            console.error("Error al enviar:", error);
            Swal.fire('Error', 'Error de conexión con la API', 'error');
        }
    }
</script>
@endsection