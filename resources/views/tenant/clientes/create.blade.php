@extends('layouts.tenant')

@section('content')
<div class="p-6 max-w-3xl mx-auto">

    <!-- HEADER -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">
            Añadir cliente
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">
            Registre un nuevo cliente en su base de datos
        </p>
    </div>

    <!-- FORM CARD -->
    <form
        action="{{ route('tenant.clientes.store') }}"
        method="POST"
        class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl
               border border-slate-200 dark:border-slate-700 p-6 space-y-6"
    >
        @csrf

        <!-- NOMBRE / APELLIDOS -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="block mb-1 text-sm font-semibold text-slate-700 dark:text-slate-300">
                    Nombre <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="nombre"
                    required
                    value="{{ old('nombre') }}"
                    class="w-full px-4 py-2 rounded-lg
                           bg-slate-50 dark:bg-slate-700
                           border border-slate-300 dark:border-slate-600
                           text-slate-800 dark:text-slate-100
                           focus:outline-none focus:ring-2 focus:ring-teal-500"
                >
            </div>

            <div>
                <label class="block mb-1 text-sm font-semibold text-slate-700 dark:text-slate-300">
                    Apellidos <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="apellidos"
                    required
                    value="{{ old('apellidos') }}"
                    class="w-full px-4 py-2 rounded-lg
                           bg-slate-50 dark:bg-slate-700
                           border border-slate-300 dark:border-slate-600
                           text-slate-800 dark:text-slate-100
                           focus:outline-none focus:ring-2 focus:ring-teal-500"
                >
            </div>

        </div>

        <!-- TELÉFONO / EMAIL -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="block mb-1 text-sm font-semibold text-slate-700 dark:text-slate-300">
                    Teléfono
                </label>
                <input
                    type="text"
                    name="telefono"
                    value="{{ old('telefono') }}"
                    class="w-full px-4 py-2 rounded-lg
                           bg-slate-50 dark:bg-slate-700
                           border border-slate-300 dark:border-slate-600
                           text-slate-800 dark:text-slate-100
                           focus:outline-none focus:ring-2 focus:ring-teal-500"
                >
            </div>

            <div>
                <label class="block mb-1 text-sm font-semibold text-slate-700 dark:text-slate-300">
                    Email
                </label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full px-4 py-2 rounded-lg
                           bg-slate-50 dark:bg-slate-700
                           border border-slate-300 dark:border-slate-600
                           text-slate-800 dark:text-slate-100
                           focus:outline-none focus:ring-2 focus:ring-teal-500"
                >
            </div>

        </div>

        <!-- DIRECCIÓN -->
        <div>
            <label class="block mb-1 text-sm font-semibold text-slate-700 dark:text-slate-300">
                Dirección
            </label>
            <input
                type="text"
                name="direccion"
                value="{{ old('direccion') }}"
                class="w-full px-4 py-2 rounded-lg
                       bg-slate-50 dark:bg-slate-700
                       border border-slate-300 dark:border-slate-600
                       text-slate-800 dark:text-slate-100
                       focus:outline-none focus:ring-2 focus:ring-teal-500"
            >
        </div>

        <!-- NOTAS -->
        <div>
            <label class="block mb-1 text-sm font-semibold text-slate-700 dark:text-slate-300">
                Notas
            </label>
            <textarea
                name="notas"
                rows="4"
                class="w-full px-4 py-2 rounded-lg
                       bg-slate-50 dark:bg-slate-700
                       border border-slate-300 dark:border-slate-600
                       text-slate-800 dark:text-slate-100
                       focus:outline-none focus:ring-2 focus:ring-teal-500"
            >{{ old('notas') }}</textarea>
        </div>

        <!-- ACTIONS -->
        <div class="flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-700">

            <a
                href="{{ route('tenant.clientes.index') }}"
                class="px-4 py-2 rounded-lg
                       bg-slate-200 dark:bg-slate-600
                       text-slate-800 dark:text-slate-100
                       hover:bg-slate-300 dark:hover:bg-slate-500 transition"
            >
                Cancelar
            </a>

            <button
                type="submit"
                class="px-5 py-2 rounded-lg
                       bg-teal-600 hover:bg-teal-700
                       text-white font-semibold shadow-sm transition"
            >
                Guardar cliente
            </button>

        </div>

    </form>

</div>
@endsection
