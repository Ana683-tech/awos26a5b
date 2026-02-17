@extends('/layouts/app')

@section('titulo', 'productos')

@section('contenido')
<div class="max-w-screen-xl mx-auto px-4 text-gray-800">

    <div class="flex justify-end mb-6">
        <button onclick="mostrarFormulario()" style="background-color: #fce7f3;" class="text-pink-700 px-6 py-2 rounded-lg font-bold shadow-sm hover:bg-pink-200 transition">
            + Crear producto
        </button>
    </div>

    {{-- Formulario de Creación --}}
    <div id="formularioProducto" class="hidden bg-white p-6 rounded-2xl shadow-sm mb-8 border border-gray-100">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Nuevo Producto</h2>
        <form method="POST" action="{{ route('productos.store') }}" enctype="multipart/form-data">
            @csrf
            <input name="nombre" placeholder="Nombre del producto" class="border border-gray-200 p-2.5 w-full mb-3 rounded-lg text-sm" required>
            <textarea name="descripcion" placeholder="Descripción detallada" class="border border-gray-200 p-2.5 w-full mb-3 rounded-lg text-sm" required></textarea>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <input type="number" step="0.01" name="precio" placeholder="Precio ($)" class="border border-gray-200 p-2.5 rounded-lg text-sm" required>
                <input type="number" name="stock" placeholder="Cantidad inicial (Stock)" class="border border-gray-200 p-2.5 rounded-lg text-sm" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="text-[10px] font-bold uppercase text-gray-400">Imagen 1</label>
                    <input type="file" name="imagen1" class="text-xs w-full mt-1">
                </div>
                <div>
                    <label class="text-[10px] font-bold uppercase text-gray-400">Imagen 2</label>
                    <input type="file" name="imagen2" class="text-xs w-full mt-1">
                </div>
                <div>
                    <label class="text-[10px] font-bold uppercase text-gray-400">Imagen 3</label>
                    <input type="file" name="imagen3" class="text-xs w-full mt-1">
                </div>
            </div>

            <button style="background-color: #fce7f3;" class="text-pink-700 px-8 py-2.5 rounded-lg font-bold shadow-sm hover:bg-pink-200 transition">Guardar Producto</button>
            <button type="button" onclick="cancelarFormulario()" class="ml-4 text-gray-400 text-sm hover:underline">Cancelar</button>
        </form>
    </div>

    {{-- Listado de Productos --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse ($productos as $producto)
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col">
                
                <div class="grid grid-cols-3 gap-1 mb-4">
                    <img src="{{ $producto->imagen1 ? asset($producto->imagen1) : 'https://via.placeholder.com/150' }}" class="w-full h-24 object-cover rounded-l-xl border border-gray-50">
                    <img src="{{ $producto->imagen2 ? asset($producto->imagen2) : 'https://via.placeholder.com/150' }}" class="w-full h-24 object-cover border-gray-50">
                    <img src="{{ $producto->imagen3 ? asset($producto->imagen3) : 'https://via.placeholder.com/150' }}" class="w-full h-24 object-cover rounded-r-xl border border-gray-50">
                </div>

                <h3 class="font-bold text-lg text-gray-800">{{ $producto->nombre }}</h3>
                <p class="text-sm text-gray-500 mb-4 flex-grow">{{ $producto->descripcion }}</p>
                
                <div class="flex justify-between items-center mb-4">
                    <span class="text-pink-600 font-extrabold text-xl">$ {{ number_format($producto->precio, 2) }}</span>
                    <span class="bg-gray-50 text-gray-600 px-3 py-1 rounded-full text-xs font-bold border border-gray-100">
                        Stock: {{ $producto->stock }}
                    </span>
                </div>

                <div class="flex gap-2 mb-2">
                    <button onclick="editarProducto({{ $producto->id_producto }})" class="flex-1 bg-gray-100 text-gray-600 py-2 rounded-lg text-xs font-bold hover:bg-gray-200 transition">Editar</button>
                    <form action="{{ route('productos.destroy', $producto->id_producto) }}" method="POST" onsubmit="return confirm('¿Borrar producto?');" class="flex-1">
                        @csrf @method('DELETE')
                        <button class="w-full bg-rose-50 text-rose-600 py-2 rounded-lg text-xs font-bold hover:bg-rose-100 transition">Eliminar</button>
                    </form>
                </div>

                {{-- FORMULARIO EDICIÓN ACTUALIZADO --}}
                <form id="editar-{{ $producto->id_producto }}" action="{{ route('productos.update', $producto->id_producto) }}" 
                      method="POST" enctype="multipart/form-data" class="hidden mt-4 pt-4 border-t border-dashed border-gray-100">
                    @csrf @method('PUT')
                    
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Nombre</label>
                    <input name="nombre" value="{{ $producto->nombre }}" class="border border-gray-200 p-2 w-full mb-2 rounded-lg text-sm">
                    
                    <div class="grid grid-cols-2 gap-2 mb-2">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Precio</label>
                            <input type="number" step="0.01" name="precio" value="{{ $producto->precio }}" class="border border-gray-200 p-2 w-full rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Stock Actual</label>
                            <input type="number" name="stock" value="{{ $producto->stock }}" class="border border-gray-200 p-2 w-full rounded-lg text-sm">
                        </div>
                    </div>
                    
                    <div class="space-y-2 mt-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Actualizar Fotos:</p>
                        <input type="file" name="imagen1" class="text-[10px] w-full">
                        <input type="file" name="imagen2" class="text-[10px] w-full">
                        <input type="file" name="imagen3" class="text-[10px] w-full">
                    </div>

                    <button style="background-color: #fce7f3;" class="text-pink-700 w-full mt-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-pink-200 transition">
                        Actualizar Producto
                    </button>
                </form>
            </div>
        @empty
            <div class="col-span-full py-20 text-center">
                <p class="text-gray-400 italic">No hay productos registrados en el inventario.</p>
            </div>
        @endforelse
    </div>
</div>

<script>
    function mostrarFormulario() { document.getElementById('formularioProducto').classList.remove('hidden'); }
    function cancelarFormulario() { document.getElementById('formularioProducto').classList.add('hidden'); }
    function editarProducto(id) { document.getElementById('editar-' + id).classList.toggle('hidden'); }
</script>
@endsection