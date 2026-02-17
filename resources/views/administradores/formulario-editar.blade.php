@extends('/layouts/app')

@section('titulo','editar administrador')

@section('contenido')
<section class="bg-white dark:bg-gray-900 mt-24">
  <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">

      <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">
          Actualizar administrador
      </h2>

      <form action="{{ route('empleados.update', $empleado->id_empleado) }}" 
            method="POST"
            enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

              <div class="sm:col-span-2">
                  <label class="block mb-2 text-sm font-medium">Nombre</label>
                  <input type="text" name="nombre" value="{{ $empleado->nombre }}"
                         class="w-full p-2 border rounded" required>
              </div>

              <div class="sm:col-span-2">
                  <label class="block mb-2 text-sm font-medium">Apellido</label>
                  <input type="text" name="apellido" value="{{ $empleado->apellido }}"
                         class="w-full p-2 border rounded">
              </div>

              <div class="w-full">
                  <label class="block mb-2 text-sm font-medium">Salario</label>
                  <input type="number" step="0.01" name="salario"
                         value="{{ $empleado->salario }}"
                         class="w-full p-2 border rounded">
              </div>

              <div class="w-full">
                  <label class="block mb-2 text-sm font-medium">Usuario</label>
                  <input type="text" name="usuario"
                         value="{{ $empleado->usuario }}"
                         class="w-full p-2 border rounded">
              </div>

              <div class="sm:col-span-2">
                  <label class="block mb-2 text-sm font-medium">Puesto</label>
                  <input type="text" name="puesto"
                         value="{{ $empleado->puesto }}"
                         class="w-full p-2 border rounded">
              </div>

              <div class="sm:col-span-2">
                  <label class="block mb-2 text-sm font-medium">Estado del empleado</label>
                  <select name="estado" class="w-full p-2 border rounded">
                      <option value="1" {{ $empleado->estado == 1 ? 'selected' : '' }}>
                          Activo
                      </option>
                      <option value="0" {{ $empleado->estado == 0 ? 'selected' : '' }}>
                          Inactivo
                      </option>
                  </select>
              </div>

              <div class="sm:col-span-2">
                  <label class="block mb-2 text-sm font-medium">
                      Nueva contraseña (opcional)
                  </label>
                  <input type="password" name="contrasena"
                         class="w-full p-2 border rounded">
              </div>

              {{-- CAMBIAR FOTO --}}
              <div class="sm:col-span-2">
                  <label class="block mb-2 text-sm font-medium">
                      Cambiar foto
                  </label>
                  <input type="file" name="imagen"
                         accept="image/png, image/jpeg"
                         class="w-full p-2 border rounded">
              </div>

              {{-- MOSTRAR FOTO ACTUAL --}}
              @if($empleado->imagen)
                  <div class="sm:col-span-2">
                      <label class="block mb-2 text-sm font-medium">
                          Foto actual
                      </label>
                      <img src="{{ asset($empleado->imagen) }}"
                           class="w-32 h-32 rounded-full object-cover">
                  </div>
              @endif

              <div class="sm:col-span-2 text-right">
                  <button type="submit"
                          class="px-6 py-2 bg-green-600 text-white rounded">
                      Guardar cambios
                  </button>
              </div>

          </div>
      </form>

  </div>
</section>
@endsection
