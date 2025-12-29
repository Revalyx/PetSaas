@extends('layouts.tenant')

@section('content')
<div
    class="p-6"
    x-data="{
        search: '',
        deleteId: null,
        openModal: false,
        pets: @js($mascotas->map(fn($m) => [
            'id' => $m->id,
            'nombre' => $m->nombre,
            'cliente' => $m->cliente->nombre ?? '',
            'especie' => $m->especie,
            'raza' => $m->raza,
            'edad' => $m->edad,
        ])),
        get filtered() {
            if (this.search === '') return this.pets

            const q = this.search.toLowerCase()
            return this.pets.filter(p =>
                (`${p.nombre} ${p.cliente} ${p.especie} ${p.raza} ${p.edad}`)
                    .toLowerCase()
                    .includes(q)
            )
        }
    }"
>

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">
                Mascotas
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Gestión y búsqueda de mascotas registradas
            </p>
        </div>

        <div class="flex gap-3 w-full sm:w-auto">
            <!-- BUSCADOR -->
            <div class="relative w-full sm:w-72">
                <input
                    type="text"
                    x-model="search"
                    placeholder="Buscar mascota…"
                    class="w-full pl-10 pr-10 py-2 rounded-lg
                           bg-white dark:bg-slate-700
                           border border-slate-300 dark:border-slate-600
                           text-slate-800 dark:text-slate-100
                           placeholder-slate-400
                           focus:outline-none focus:ring-2 focus:ring-teal-500"
                />
                <span class="absolute left-3 top-2.5 text-slate-400">🔍</span>

                <button
                    x-show="search !== ''"
                    @click="search = ''"
                    class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600"
                >
                    ✕
                </button>
            </div>

            <a
                href="{{ route('tenant.mascotas.create') }}"
                class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg shadow-sm transition whitespace-nowrap"
            >
                Nueva mascota
            </a>
        </div>
    </div>

    <!-- CONTADOR -->
    <div class="text-sm text-slate-500 dark:text-slate-400 mb-4">
        Mostrando
        <span class="font-semibold text-slate-700 dark:text-slate-200"
              x-text="filtered.length"></span>
        mascotas
    </div>

    <!-- CARD TABLA -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">

        <!-- TABLA -->
        <div class="overflow-x-auto" x-show="filtered.length > 0">
            <table class="w-full text-left text-sm">

                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400">
                        <th class="pb-3 font-semibold">Nombre</th>
                        <th class="pb-3 font-semibold">Cliente</th>
                        <th class="pb-3 font-semibold">Especie</th>
                        <th class="pb-3 font-semibold">Raza</th>
                        <th class="pb-3 font-semibold">Edad</th>
                        <th class="pb-3 text-right font-semibold">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <template x-for="m in filtered" :key="m.id">
                        <tr
                            class="border-t border-slate-200 dark:border-slate-700
                                   hover:bg-slate-50 dark:hover:bg-slate-700/40 transition"
                        >
                            <td class="py-3 px-2 font-medium text-slate-800 dark:text-slate-100" x-text="m.nombre"></td>
                            <td class="py-3 px-2 text-slate-700 dark:text-slate-300" x-text="m.cliente"></td>
                            <td class="py-3 px-2 text-slate-700 dark:text-slate-300" x-text="m.especie"></td>
                            <td class="py-3 px-2 text-slate-700 dark:text-slate-300" x-text="m.raza"></td>
                            <td class="py-3 px-2 text-slate-700 dark:text-slate-300" x-text="m.edad"></td>

                            <td class="py-3 px-2 text-right space-x-2">
                                <a
                                    :href="`{{ url('tenant/mascotas') }}/${m.id}/editar`"
                                    class="inline-flex items-center bg-amber-500 hover:bg-amber-600
                                           text-white px-3 py-1.5 rounded-md text-xs shadow-sm transition"
                                >
                                    Editar
                                </a>

                                <button
                                    @click="deleteId = m.id; openModal = true"
                                    class="inline-flex items-center bg-red-600 hover:bg-red-700
                                           text-white px-3 py-1.5 rounded-md text-xs shadow-sm transition"
                                >
                                    Borrar
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>

            </table>
        </div>

        <!-- SIN RESULTADOS -->
        <div
            x-show="filtered.length === 0"
            class="text-center py-10 text-slate-500 dark:text-slate-400"
        >
            No se encontraron mascotas que coincidan con la búsqueda.
        </div>
    </div>

    <!-- MODAL CONFIRMACIÓN BORRADO -->
    <div
        x-show="openModal"
        class="fixed inset-0 flex items-center justify-center bg-black/50 z-50"
        x-transition
    >
        <div
            class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl p-6 w-full max-w-md
                   border border-slate-200 dark:border-slate-700"
            @click.away="openModal = false"
        >
            <h2 class="text-xl font-bold mb-4 text-slate-800 dark:text-slate-100">
                Eliminar mascota
            </h2>

            <p class="text-slate-600 dark:text-slate-300 mb-6">
                ¿Está seguro de que desea eliminar esta mascota?
                <br>
                <span class="font-semibold text-red-600 dark:text-red-400">
                    Esta acción no se puede deshacer.
                </span>
            </p>

            <div class="flex justify-end gap-3">
                <button
                    class="px-4 py-2 rounded-lg bg-slate-200 dark:bg-slate-600
                           text-slate-800 dark:text-slate-100
                           hover:bg-slate-300 dark:hover:bg-slate-500 transition"
                    @click="openModal = false"
                >
                    Cancelar
                </button>

                <form method="POST" :action="`{{ url('tenant/mascotas') }}/${deleteId}`">
                    @csrf
                    @method('DELETE')

                    <button
                        class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700
                               text-white transition"
                    >
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
