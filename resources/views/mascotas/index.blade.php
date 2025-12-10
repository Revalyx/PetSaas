@extends('layouts.tenant')

@section('content')
<div class="p-6" x-data="{ openModal: false, deleteId: null }">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">Mascotas</h1>

        <a href="{{ route('tenant.mascotas.create') }}"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow">
            Nueva Mascota
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                    <th class="px-4 py-3 text-left">Nombre</th>
                    <th class="px-4 py-3 text-left">Cliente</th>
                    <th class="px-4 py-3 text-left">Especie</th>
                    <th class="px-4 py-3 text-left">Raza</th>
                    <th class="px-4 py-3 text-left">Edad</th>
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($mascotas as $m)
                <tr class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $m->nombre }}</td>
                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $m->cliente->nombre }}</td>
                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $m->especie }}</td>
                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $m->raza }}</td>
                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $m->edad }}</td>

                    <td class="px-4 py-3 text-center flex justify-center gap-3">

                        {{-- Botón Editar --}}
                        <a href="{{ route('tenant.mascotas.edit', $m->id) }}"
                           class="p-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg shadow">
                            ✏️
                        </a>

                        {{-- Botón Borrar (abre modal) --}}
                        <button
                            @click="deleteId = {{ $m->id }}; openModal = true;"
                            class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg shadow">
                            🗑️
                        </button>

                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>


    <!-- ========================================================= -->
    <!-- MODAL DE CONFIRMACIÓN DE BORRADO -->
    <!-- ========================================================= -->
    <div
        x-show="openModal"
        x-transition
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
    >
        <div class="bg-white dark:bg-gray-800 w-96 p-6 rounded-xl shadow-lg"
             @click.away="openModal = false">

            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                Eliminar Mascota
            </h2>

            <p class="text-gray-700 dark:text-gray-300 mb-6">
                ¿Está seguro de que desea eliminar esta mascota?
                Esta acción no se puede deshacer.
            </p>

            <div class="flex justify-end space-x-3">
                <button
                    @click="openModal = false"
                    class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-gray-100 rounded">
                    Cancelar
                </button>

                <form method="POST" :action="'{{ url('tenant/mascotas') }}/' + deleteId">
                    @csrf
                    @method('DELETE')

                    <button
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">
                        Eliminar
                    </button>
                </form>
            </div>

        </div>
    </div>

</div>
@endsection
