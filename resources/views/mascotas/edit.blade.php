@extends('layouts.tenant')

@section('content')
<div class="p-6 max-w-xl">

    <h1 class="text-2xl font-bold mb-6">Editar Mascota</h1>

    <form method="POST" action="{{ route('tenant.mascotas.update', $mascota->id) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-medium mb-1">Cliente</label>
            <select name="cliente_id"
                    class="w-full border rounded p-2 bg-white text-black dark:bg-gray-800 dark:text-white">
                @foreach ($clientes as $c)
                    <option value="{{ $c->id }}" {{ $mascota->cliente_id == $c->id ? 'selected' : '' }}>
                        {{ $c->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-medium mb-1">Nombre</label>
            <input name="nombre" type="text"
                   value="{{ $mascota->nombre }}"
                   class="w-full border rounded p-2 bg-white text-black dark:bg-gray-800 dark:text-white"
                   required>
        </div>

        <div>
            <label class="block font-medium mb-1">Especie</label>
            <input name="especie" type="text"
                   value="{{ $mascota->especie }}"
                   class="w-full border rounded p-2 bg-white text-black dark:bg-gray-800 dark:text-white">
        </div>

        <div>
            <label class="block font-medium mb-1">Raza</label>
            <input name="raza" type="text"
                   value="{{ $mascota->raza }}"
                   class="w-full border rounded p-2 bg-white text-black dark:bg-gray-800 dark:text-white">
        </div>

        <div>
            <label class="block font-medium mb-1">Edad</label>
            <input name="edad" type="number"
                   value="{{ $mascota->edad }}"
                   class="w-full border rounded p-2 bg-white text-black dark:bg-gray-800 dark:text-white">
        </div>

        <div>
            <label class="block font-medium mb-1">Notas</label>
            <textarea name="notas"
                      class="w-full border rounded p-2 bg-white text-black dark:bg-gray-800 dark:text-white">{{ $mascota->notas }}</textarea>
        </div>

        <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
            Guardar Cambios
        </button>

    </form>

</div>
@endsection
