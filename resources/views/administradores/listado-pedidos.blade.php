@extends('layouts.app')

@section('titulo', 'Gestión de Pedidos')

@section('contenido')
<div class="max-w-screen-xl mx-auto px-4 mt-6">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-serif font-bold text-gray-800">Gestión de Ordenes</h2>
        <a href="{{ url('pedido/formulario-pedido') }}" class="bg-pink-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg hover:bg-pink-700 transition">
            <i class="fas fa-plus mr-2"></i> Nuevo Pedido
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-pink-50 border-b border-pink-100">
                <tr>
                    <th class="p-5 font-bold text-pink-700">Folio</th>
                    <th class="p-5 font-bold text-pink-700">Cliente (ID)</th>
                    <th class="p-5 font-bold text-pink-700">Fecha</th>
                    <th class="p-5 font-bold text-pink-700">Monto Total</th>
                    <th class="p-5 font-bold text-pink-700">Estado Pago</th>
                    <th class="p-5 font-bold text-center text-pink-700">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pedidos as $pedido)
                <tr class="hover:bg-pink-50/30 transition-colors">
                    <td class="p-5 font-black text-gray-700">#{{ $pedido->id_pedido }}</td>
                    <td class="p-5 font-medium text-gray-600">ID: {{ $pedido->id_cliente }}</td>
                    <td class="p-5 text-gray-500">{{ $pedido->fecha_pedido }}</td>
                    <td class="p-5 font-bold text-gray-800">${{ number_format($pedido->total, 2) }}</td>
                    <td class="p-5">
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $pedido->estado_pago == 'Pendiente' ? 'bg-orange-100 text-orange-600' : 'bg-green-100 text-green-600' }}">
                            <i class="fas fa-circle text-[8px] mr-1"></i> {{ $pedido->estado_pago }}
                        </span>
                    </td>
                    <td class="p-5 flex justify-center space-x-3">
                        <button onclick="verTicket({{ $pedido->id_pedido }})" class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm flex items-center justify-center" title="Ver Detalle">
                            <i class="fas fa-receipt"></i>
                        </button>
                        
                        <button onclick="eliminarPedido({{ $pedido->id_pedido }})" class="w-10 h-10 rounded-full bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm flex items-center justify-center" title="Eliminar Pedido">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-10 text-center text-gray-400 italic">
                        No hay pedidos registrados actualmente.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Función para ver el detalle (Ticket)
   // Función para ver el detalle (Ticket)
async function verTicket(id) {
    Swal.fire({ title: 'Consultando Pedido...', didOpen: () => { Swal.showLoading() } });

    try {
        const response = await fetch(`/api/pedidos/${id}`);
        const data = await response.json();

        if (data.status) {
            const pedido = data.pedido;
            let filas = '';

            // Verificamos que existan detalles antes de iterar
            if (pedido.detalles && pedido.detalles.length > 0) {
                pedido.detalles.forEach(item => {
                    // Si la relación producto existe, sacamos el nombre, si no, mostramos el ID
                    const nombre = item.producto ? item.producto.nombre : `Producto #${item.id_producto}`;
                    const precio = parseFloat(item.precio_unitario).toFixed(2);
                    const subtotal = (item.cantidad * item.precio_unitario).toFixed(2);

                    filas += `
                        <tr style="border-bottom: 1px dotted #ccc;">
                            <td style="padding: 10px 5px; text-align: left;">
                                <strong>${nombre}</strong><br>
                                <small style="color: #666;">$${precio} c/u</small>
                            </td>
                            <td style="padding: 10px 5px; text-align: center;">${item.cantidad}</td>
                            <td style="padding: 10px 5px; text-align: right;">$${subtotal}</td>
                        </tr>
                    `;
                });
            } else {
                filas = '<tr><td colspan="3" style="text-align:center; padding:20px;">No hay productos registrados</td></tr>';
            }

            Swal.fire({
                title: `Ticket de Orden #${pedido.id_pedido}`,
                html: `
                    <div style="text-align: left; font-size: 0.85rem; margin-bottom: 15px; border-left: 4px solid #db2777; padding-left: 10px;">
                        <p style="margin: 0;"><strong>Fecha:</strong> ${pedido.fecha_pedido}</p>
                        <p style="margin: 0;"><strong>Cliente:</strong> ID #${pedido.id_cliente}</p>
                    </div>
                    <table style="width: 100%; border-collapse: collapse; font-family: sans-serif; font-size: 0.9rem;">
                        <thead style="border-bottom: 2px solid #db2777; background: #fff1f2;">
                            <tr>
                                <th style="padding: 8px; text-align: left;">Producto</th>
                                <th style="padding: 8px; text-align: center;">Cant.</th>
                                <th style="padding: 8px; text-align: right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>${filas}</tbody>
                    </table>
                    <div style="margin-top: 20px; text-align: right; font-size: 1.3rem; font-weight: 900; color: #db2777;">
                        TOTAL: $${parseFloat(pedido.total).toFixed(2)}
                    </div>
                `,
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#db2777',
                width: '500px'
            });
        }
    } catch (error) {
        Swal.fire('Error', 'No se pudo cargar el ticket. Revisa la consola (F12).', 'error');
    }
}
    // Función para ELIMINAR el pedido de la base de datos
    async function eliminarPedido(id) {
        const result = await Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción borrará el pedido #" + id + " y todos sus productos asociados de forma permanente.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48', // Color rojo
            cancelButtonColor: '#6b7280', // Color gris
            confirmButtonText: 'Sí, eliminar pedido',
            cancelButtonText: 'Cancelar'
        });

        if (result.isConfirmed) {
            try {
                // Realizamos la petición DELETE a la API
                const response = await fetch(`/api/pedidos/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // Token de seguridad de Laravel
                        'Accept': 'application/json'
                    }
                });

                const resData = await response.json();

                if (response.ok && resData.status) {
                    Swal.fire(
                        '¡Eliminado!',
                        'El pedido ha sido borrado de la base de datos.',
                        'success'
                    ).then(() => {
                        location.reload(); // Recargamos la página para ver los cambios
                    });
                } else {
                    Swal.fire('Error', 'No se pudo eliminar: ' + (resData.message || 'Error desconocido'), 'error');
                }
            } catch (error) {
                console.error("Error:", error);
                Swal.fire('Error de red', 'No se pudo conectar con el servidor.', 'error');
            }
        }
    }
</script>
@endsection