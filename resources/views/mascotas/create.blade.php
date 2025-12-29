@extends('layouts.tenant')

@section('title', 'Nueva Mascota')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    <!-- HEADER -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">
            Nueva mascota
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">
            Registre una nueva mascota asociada a un cliente
        </p>
    </div>

    <!-- CARD FORM -->
    <form
        method="POST"
        action="{{ route('tenant.mascotas.store') }}"
        class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl
               border border-slate-200 dark:border-slate-700
               p-6 grid grid-cols-1 md:grid-cols-2 gap-6"
    >
        @csrf

        <!-- CLIENTE (2 columnas) -->
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-300">
                Cliente <span class="text-red-500">*</span>
            </label>
            <select
                name="cliente_id"
                required
                class="w-full rounded-lg px-4 py-2
                       bg-slate-50 dark:bg-slate-700
                       border border-slate-300 dark:border-slate-600
                       text-slate-800 dark:text-slate-100
                       focus:outline-none focus:ring-2 focus:ring-teal-500"
            >
                @foreach ($clientes as $c)
                    <option value="{{ $c->id }}">
                        {{ $c->nombre }} {{ $c->apellidos ?? '' }}
                        — {{ $c->email ?? 'Sin email' }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- NOMBRE -->
        <div>
            <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-300">
                Nombre <span class="text-red-500">*</span>
            </label>
            <input
                name="nombre"
                required
                placeholder="Thor, Lola, Nilo…"
                class="w-full rounded-lg px-4 py-2
                       bg-slate-50 dark:bg-slate-700
                       border border-slate-300 dark:border-slate-600
                       text-slate-800 dark:text-slate-100
                       focus:outline-none focus:ring-2 focus:ring-teal-500"
            >
        </div>

        <!-- ESPECIE -->
        <div>
            <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-300">
                Especie
            </label>
            <input
                name="especie"
                placeholder="Perro, Gato…"
                class="w-full rounded-lg px-4 py-2
                       bg-slate-50 dark:bg-slate-700
                       border border-slate-300 dark:border-slate-600
                       text-slate-800 dark:text-slate-100
                       focus:outline-none focus:ring-2 focus:ring-teal-500"
            >
        </div>

        <!-- RAZA -->
        <div>
            <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-300">
                Raza
            </label>
            <input
                name="raza"
                placeholder="Labrador, Siamés…"
                class="w-full rounded-lg px-4 py-2
                       bg-slate-50 dark:bg-slate-700
                       border border-slate-300 dark:border-slate-600
                       text-slate-800 dark:text-slate-100
                       focus:outline-none focus:ring-2 focus:ring-teal-500"
            >
        </div>

        <!-- EDAD -->
        <div>
            <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-300">
                Edad
            </label>
            <input
                name="edad"
                type="number"
                min="0"
                placeholder="En años"
                class="w-full rounded-lg px-4 py-2
                       bg-slate-50 dark:bg-slate-700
                       border border-slate-300 dark:border-slate-600
                       text-slate-800 dark:text-slate-100
                       focus:outline-none focus:ring-2 focus:ring-teal-500"
            >
        </div>

        <!-- NOTAS (2 columnas) -->
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-300">
                Notas
            </label>
            <textarea
                name="notas"
                rows="4"
                placeholder="Comportamiento, alergias, observaciones…"
                class="w-full rounded-lg px-4 py-2
                       bg-slate-50 dark:bg-slate-700
                       border border-slate-300 dark:border-slate-600
                       text-slate-800 dark:text-slate-100
                       resize-none
                       focus:outline-none focus:ring-2 focus:ring-teal-500"
            ></textarea>
        </div>

        <!-- ACTIONS (2 columnas) -->
        <div class="md:col-span-2 flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-700">

            <a
                href="{{ route('tenant.mascotas.index') }}"
                class="px-4 py-2 rounded-lg
                       bg-slate-200 dark:bg-slate-600
                       text-slate-800 dark:text-slate-100
                       hover:bg-slate-300 dark:hover:bg-slate-500
                       transition"
            >
                Cancelar
            </a>

            <button
                type="submit"
                class="px-5 py-2 rounded-lg
                       bg-teal-600 hover:bg-teal-700
                       text-white font-semibold
                       shadow-sm transition"
            >
                Guardar mascota
            </button>

        </div>

    </form>

</div>
@endsection
