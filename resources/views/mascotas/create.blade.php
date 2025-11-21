@extends('layouts.tenant')

@section('title', 'Nueva Mascota')

@section('content')

<div class="bg-gray-900/20 dark:bg-gray-800/20 p-6 rounded-xl shadow-xl max-w-4xl mx-auto">

    <h2 class="text-xl font-bold text-center mb-4">Nueva Mascota</h2>

    <form method="POST" action="{{ route('tenant.mascotas.store') }}" class="grid grid-cols-2 gap-4">
        @csrf

        {{-- CLIENTE (ocupa 2 columnas) --}}
        <div class="col-span-2">
            <label class="block text-sm font-medium mb-1">Cliente</label>
            <select name="cliente_id"
                class="w-full border rounded-md p-2 text-sm dark:bg-gray-800 dark:text-white h-9">
                @foreach ($clientes as $c)
                    <option value="{{ $c->id }}">
                        {{ $c->nombre }} {{ $c->apellidos ?? '' }} — {{ $c->email ?? 'Sin email' }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- NOMBRE --}}
        <div>
            <label class="block text-sm font-medium mb-1">Nombre</label>
            <input name="nombre" required
                   class="w-full border rounded-md p-2 text-sm dark:bg-gray-800 dark:text-white h-9"
                   placeholder="Thor, Lola, Nilo…">
        </div>

        {{-- ESPECIE --}}
        <div>
            <label class="block text-sm font-medium mb-1">Especie</label>
            <input name="especie"
                   class="w-full border rounded-md p-2 text-sm dark:bg-gray-800 dark:text-white h-9"
                   placeholder="Perro, Gato…">
        </div>

        {{-- RAZA --}}
        <div>
            <label class="block text-sm font-medium mb-1">Raza</label>
            <input name="raza"
                   class="w-full border rounded-md p-2 text-sm dark:bg-gray-800 dark:text-white h-9"
                   placeholder="Labrador, Siamés…">
        </div>

        {{-- EDAD --}}
        <div>
            <label class="block text-sm font-medium mb-1">Edad</label>
            <input name="edad" type="number"
                   class="w-full border rounded-md p-2 text-sm dark:bg-gray-800 dark:text-white h-9"
                   placeholder="En años">
        </div>

        {{-- NOTAS (ocupa 2 columnas y bajita) --}}
        <div class="col-span-2">
            <label class="block text-sm font-medium mb-1">Notas</label>
            <textarea name="notas"
                class="w-full border rounded-md p-2 text-sm dark:bg-gray-800 dark:text-white h-20 resize-none"
                placeholder="Comportamiento, alergias…"></textarea>
        </div>

        {{-- BOTÓN (ocupa 2 columnas) --}}
        <div class="col-span-2 flex justify-end mt-1">
            <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm">
                Guardar
            </button>
        </div>

    </form>

</div>

@endsection
