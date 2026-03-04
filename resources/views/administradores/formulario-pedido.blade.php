@extends('layouts.app')

@section('titulo', 'Crear Pedido')

@section('contenido')
<div class="max-w-screen-xl mx-auto mt-10 px-4">
    <div class="bg-white p-8 rounded-3xl shadow-lg border border-pink-100 mb-8">
        <h2 class="text-3xl font-serif font-bold mb-6 text-pink-700">Registrar Pedido Nuevo</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Seleccionar Cliente</label>
                    <select id="id_cliente" class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-pink-500 focus:border-pink-500">
                        <option value="">-- Seleccione un cliente --</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id_cliente }}">{{ $cliente->nombre }} {{ $cliente->apellidos }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Número de Pedido (Folio)</label>
                    <input type="number" id="id_pedido" class="w-full border-gray-200 rounded-xl px-4 py-3" placeholder="Ej. 101">
                </div>

                <div class="bg-pink-50 p-6 rounded-2xl border border-pink-100">
                    <p class="text-pink-800 font-semibold mb-1">Resumen del Ticket:</p>
                    <div class="text-4xl font-black text-pink-600">
                        Total: $<span id="total_acumulado">0.00</span>
                    </div>
                </div>

                <button onclick="finalizarPedido()" class="bg-pink-600 text-white px-8 py-4 rounded-2xl hover:bg-pink-700 w-full font-bold text-lg shadow-lg shadow-pink-200 transition-all transform hover:scale-[1.02]">
                    <i class="fas fa-check-circle mr-2"></i> Guardar Pedido en Sistema
                </button>
            </div>

            <div class="bg-gray-50 p-6 rounded-3xl border border-gray-100">
                <h3 class="font-bold text-gray-700 mb-4 flex items-center">
                    <i class="fas fa-shopping-basket mr-2 text-pink-500"></i> Productos Seleccionados
                </h3>
                <div id="contenedor-carrito" class="space-y-3 max-h-[300px] overflow-y-auto mb-4 pr-2">
                    <p id="carrito-vacio" class="text-gray-400 italic text-center py-10">El carrito está vacío</p>
                </div>
                <div class="flex justify-between items-center border-t border-gray-200 pt-4 font-bold text-gray-800">
                    <span>Total de piezas:</span>
                    <span id="contador" class="bg-pink-200 text-pink-700 px-3 py-1 rounded-full text-sm">0</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <h2 class="text-2xl font-bold mb-8 text-gray-800">Catálogo de Repostería</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
            @foreach($productos as $producto)
            <div class="group bg-white p-4 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:border-pink-200 transition-all">
                <div class="relative overflow-hidden rounded-xl mb-4">
                    <img src="{{ asset($producto->imagen1) }}" class="w-full h-40 object-cover transform group-hover:scale-110 transition-transform duration-500" onerror="this.src='https://via.placeholder.com/150'">
                    @if($producto->stock <= 0)
                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                            <span class="text-white font-bold rotate-12 border-2 border-white px-2">AGOTADO</span>
                        </div>
                    @endif
                </div>
                <h3 class="font-bold text-gray-800 mb-1">{{ $producto->nombre }}</h3>
                <p class="text-xs {{ $producto->stock > 0 ? 'text-green-600' : 'text-red-600' }} font-bold mb-2">
                    Stock disponible: {{ $producto->stock }}
                </p>
                <p class="text-pink-600 font-black text-xl mb-4">${{ number_format($producto->precio, 2) }}</p>
                
                <button onclick="agregarAlCarrito({{ $producto->id_producto }}, '{{ $producto->nombre }}', {{ $producto->precio }}, {{ $producto->stock }})"
                        {{ $producto->stock <= 0 ? 'disabled' : '' }}
                        class="{{ $producto->stock > 0 ? 'bg-gray-800 hover:bg-pink-600' : 'bg-gray-300 cursor-not-allowed' }} text-white px-4 py-2.5 rounded-xl w-full transition-colors font-semibold text-sm">
                    {{ $producto->stock > 0 ? '+ Agregar al Ticket' : 'Sin existencias' }}
                </button>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let carrito = [];
    let totalGlobal = 0;

    function agregarAlCarrito(id, nombre, precio, stockMax) {
        const existe = carrito.find(p => p.id_producto === id);
        
        if (existe) {
            if (existe.cantidad < stockMax) {
                existe.cantidad++;
            } else {
                Swal.fire('Límite de Stock', 'No puedes agregar más de este producto.', 'info');
                return;
            }
        } else {
            carrito.push({ id_producto: id, nombre: nombre, precio: parseFloat(precio), cantidad: 1, stockMax: stockMax });
        }
        actualizarVistaCarrito();
    }

    function actualizarVistaCarrito() {
        const contenedor = document.getElementById('contenedor-carrito');
        const emptyMsg = document.getElementById('carrito-vacio');
        
        contenedor.innerHTML = '';
        totalGlobal = 0;
        let totalItems = 0;

        if (carrito.length === 0) {
            contenedor.innerHTML = '<p id="carrito-vacio" class="text-gray-400 italic text-center py-10">El carrito está vacío</p>';
        } else {
            carrito.forEach((p) => {
                const subtotal = p.precio * p.cantidad;
                totalGlobal += subtotal;
                totalItems += p.cantidad;

                contenedor.innerHTML += `
                    <div class="flex justify-between items-center bg-white p-3 rounded-xl border border-gray-100 shadow-sm">
                        <div>
                            <p class="font-bold text-gray-800 text-sm">${p.nombre}</p>
                            <p class="text-xs text-gray-500">${p.cantidad} unidad(es) x $${p.precio.toFixed(2)}</p>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-pink-600">$${subtotal.toFixed(2)}</span>
                            <button onclick="eliminarDelCarrito(${p.id_producto})" class="block text-[10px] text-red-400 hover:underline">Quitar</button>
                        </div>
                    </div>
                `;
            });
        }

        document.getElementById('contador').textContent = totalItems;
        document.getElementById('total_acumulado').textContent = totalGlobal.toLocaleString('es-MX', { minimumFractionDigits: 2 });
    }

    function eliminarDelCarrito(id) {
        carrito = carrito.filter(p => p.id_producto !== id);
        actualizarVistaCarrito();
    }

    async function finalizarPedido() {
        const id_cliente = document.getElementById('id_cliente').value;
        const id_pedido = document.getElementById('id_pedido').value;

        if (!id_cliente || !id_pedido || carrito.length === 0) {
            Swal.fire('Campos incompletos', 'Asegúrate de tener cliente, folio y productos.', 'warning');
            return;
        }

        const datos = {
            id_pedido: id_pedido,
            id_cliente: id_cliente,
            total: totalGlobal,
            productos: carrito 
        };

        try {
            const response = await fetch('/api/pedidos', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(datos)
            });

            const res = await response.json();
            if (response.ok) {
                Swal.fire('¡Éxito!', 'Pedido registrado y stock descontado.', 'success')
                .then(() => {
                    window.location.href = "{{ url('/pedidos/listado') }}";
                });
            } else {
                Swal.fire('Error de Stock', res.message || 'Error al guardar', 'error');
            }
        } catch (e) {
            Swal.fire('Error', 'Error de conexión con el servidor', 'error');
        }
    }
</script>
@endsection