@extends('layouts.tenant')

@section('content')
<div class="p-6">

    <h1 class="text-2xl font-semibold mb-6">Editar cliente</h1>

    <div class="bg-white dark:bg-gray-800 rounded shadow p-6 max-w-2xl">

        {{-- Mensajes de error --}}
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tenant.clientes.update', $cliente->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Nombre --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Nombre *</label>
                <input type="text" name="nombre"
                       value="{{ old('nombre', $cliente->nombre) }}"
                       class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded p-2"
                       required>
            </div>

            {{-- Apellidos --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Apellidos *</label>
                <input type="text" name="apellidos"
                       value="{{ old('apellidos', $cliente->apellidos) }}"
                       class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded p-2"
                       required>
            </div>

            {{-- Teléfono --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Teléfono</label>
                <input type="text" name="telefono"
                       value="{{ old('telefono', $cliente->telefono) }}"
                       class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded p-2">
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Email</label>
                <input type="email" name="email"
                       value="{{ old('email', $cliente->email) }}"
                       class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded p-2">
            </div>

            {{-- Dirección --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Dirección</label>
                <input type="text" name="direccion"
                       value="{{ old('direccion', $cliente->direccion) }}"
                       class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded p-2">
            </div>

            {{-- Notas --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Notas</label>
                <textarea name="notas"
                          class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded p-2 h-32">{{ old('notas', $cliente->notas) }}</textarea>
            </div>

            {{-- Acción --}}
            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('tenant.clientes.index') }}"
                   class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded">
                    Cancelar
                </a>

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded shadow">
                    Guardar cambios
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
