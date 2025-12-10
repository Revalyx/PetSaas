@extends('layouts.tenant')

@section('content')
<div class="p-6" x-data="{ deleteId: null, openModal: false }">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Clientes</h1>

        <a href="{{ route('tenant.clientes.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
           Añadir cliente
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded shadow p-6">

        @if($clientes->isEmpty())
            <p class="text-gray-500">No hay clientes registrados aún.</p>
        @else
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-300 dark:border-gray-700">
                        <th class="pb-3">Nombre</th>
                        <th class="pb-3">Apellidos</th>
                        <th class="pb-3">Teléfono</th>
                        <th class="pb-3">Email</th>
                        <th class="pb-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($clientes as $c)
                    <tr class="border-t border-gray-300 dark:border-gray-700">
                        <td class="py-3 px-2">{{ $c->nombre }}</td>
                        <td class="py-3 px-2">{{ $c->apellidos }}</td>
                        <td class="py-3 px-2">{{ $c->telefono ?? '-' }}</td>
                        <td class="py-3 px-2">{{ $c->email ?? '-' }}</td>

                        <td class="py-3 px-2 text-right">

                            <!-- Botón Editar -->
                            <a href="{{ route('tenant.clientes.edit', $c->id) }}"
                               class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm shadow mr-2">
                                Editar
                            </a>

                            <!-- Botón Borrar → abre modal -->
                            <button
                                @click="deleteId = {{ $c->id }}; openModal = true;"
                                class="inline-block bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm shadow">
                                Borrar
                            </button>

                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        @endif
    </div>


    <!-- ========================================================= -->
    <!-- MODAL DE CONFIRMACIÓN DE BORRADO -->
    <!-- ========================================================= -->
    <div
        x-show="openModal"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
        x-transition
    >
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96"
             @click.away="openModal = false">

            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">
                Confirmar eliminación
            </h2>

            <p class="text-gray-600 dark:text-gray-300 mb-6">
                ¿Está seguro de que desea eliminar este cliente?  
                Esta acción no se puede deshacer.
            </p>

            <div class="flex justify-end space-x-3">

                <button
                    class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-100 rounded hover:bg-gray-400"
                    @click="openModal = false">
                    Cancelar
                </button>

                <form method="POST"
                      :action="'{{ url('tenant/clientes') }}/' + deleteId">
                    @csrf
                    @method('DELETE')

                    <button
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Eliminar
                    </button>
                </form>

            </div>
        </div>
    </div>

</div>
@endsection
