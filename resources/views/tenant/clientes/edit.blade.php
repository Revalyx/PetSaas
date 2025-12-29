@extends('layouts.tenant')

@section('content')
<div class="p-6 max-w-3xl mx-auto">

    <!-- HEADER -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">
            Editar cliente
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">
            Modifique los datos del cliente seleccionado
        </p>
    </div>

    <!-- CARD -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">

        {{-- MENSAJES DE ERROR --}}
        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-300 dark:border-red-700
                        bg-red-50 dark:bg-red-900/20 p-4 text-red-700 dark:text-red-300">
                <p class="font-semibold mb-2">
                    Se han encontrado errores:
                </p>
                <ul class="list-disc ml-5 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tenant.clientes.update', $cliente->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- NOMBRE -->
            <div>
                <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-300">
                    Nombre <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="nombre"
                    value="{{ old('nombre', $cliente->nombre) }}"
                    required
                    class="w-full rounded-lg px-4 py-2
                           bg-white dark:bg-slate-900
                           border border-slate-300 dark:border-slate-700
                           text-slate-800 dark:text-slate-100
                           focus:outline-none focus:ring-2 focus:ring-teal-500"
                >
            </div>

            <!-- APELLIDOS -->
            <div>
                <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-300">
                    Apellidos <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="apellidos"
                    value="{{ old('apellidos', $cliente->apellidos) }}"
                    required
                    class="w-full rounded-lg px-4 py-2
                           bg-white dark:bg-slate-900
                           border border-slate-300 dark:border-slate-700
                           text-slate-800 dark:text-slate-100
                           focus:outline-none focus:ring-2 focus:ring-teal-500"
                >
            </div>

            <!-- TELÉFONO -->
            <div>
                <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-300">
                    Teléfono
                </label>
                <input
                    type="text"
                    name="telefono"
                    value="{{ old('telefono', $cliente->telefono) }}"
                    class="w-full rounded-lg px-4 py-2
                           bg-white dark:bg-slate-900
                           border border-slate-300 dark:border-slate-700
                           text-slate-800 dark:text-slate-100
                           focus:outline-none focus:ring-2 focus:ring-teal-500"
                >
            </div>

            <!-- EMAIL -->
            <div>
                <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-300">
                    Email
                </label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $cliente->email) }}"
                    class="w-full rounded-lg px-4 py-2
                           bg-white dark:bg-slate-900
                           border border-slate-300 dark:border-slate-700
                           text-slate-800 dark:text-slate-100
                           focus:outline-none focus:ring-2 focus:ring-teal-500"
                >
            </div>

            <!-- DIRECCIÓN -->
            <div>
                <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-300">
                    Dirección
                </label>
                <input
                    type="text"
                    name="direccion"
                    value="{{ old('direccion', $cliente->direccion) }}"
                    class="w-full rounded-lg px-4 py-2
                           bg-white dark:bg-slate-900
                           border border-slate-300 dark:border-slate-700
                           text-slate-800 dark:text-slate-100
                           focus:outline-none focus:ring-2 focus:ring-teal-500"
                >
            </div>

            <!-- NOTAS -->
            <div>
                <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-300">
                    Notas
                </label>
                <textarea
                    name="notas"
                    rows="4"
                    class="w-full rounded-lg px-4 py-2
                           bg-white dark:bg-slate-900
                           border border-slate-300 dark:border-slate-700
                           text-slate-800 dark:text-slate-100
                           focus:outline-none focus:ring-2 focus:ring-teal-500"
                >{{ old('notas', $cliente->notas) }}</textarea>
            </div>

            <!-- ACCIONES -->
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-700">

                <a
                    href="{{ route('tenant.clientes.index') }}"
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
                    Guardar cambios
                </button>

            </div>

        </form>

    </div>

</div>
@endsection
