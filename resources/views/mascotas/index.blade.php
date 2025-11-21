@extends('layouts.tenant')

@section('content')
<div class="p-6">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Mascotas</h1>

        <a href="{{ route('tenant.mascotas.create') }}"
           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow">
            Nueva Mascota
        </a>
    </div>

    <div class="bg-gray-800/40 border border-gray-700 rounded-xl shadow-lg overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-700 text-gray-200 text-left">
                    <th class="px-4 py-3">Nombre</th>
                    <th class="px-4 py-3">Cliente</th>
                    <th class="px-4 py-3">Especie</th>
                    <th class="px-4 py-3">Raza</th>
                    <th class="px-4 py-3">Edad</th>
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($mascotas as $m)
                <tr class="border-t border-gray-700 hover:bg-gray-700/40 transition">
                    <td class="px-4 py-3 font-medium">{{ $m->nombre }}</td>
                    <td class="px-4 py-3">{{ $m->cliente->nombre }}</td>
                    <td class="px-4 py-3">{{ $m->especie }}</td>
                    <td class="px-4 py-3">{{ $m->raza }}</td>
                    <td class="px-4 py-3">{{ $m->edad }}</td>

                    <td class="px-4 py-3 text-center flex justify-center gap-3">

                        {{-- Botón Editar --}}
                        <a href="{{ route('tenant.mascotas.edit', $m->id) }}"
                           class="p-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 4h10M11 4v10M11 4L4 11m7-7l7 7" />
                            </svg>
                        </a>

                        {{-- Botón Borrar --}}
                        <form method="POST"
                              action="{{ route('tenant.mascotas.destroy', $m->id) }}"
                              onsubmit="return confirm('¿Seguro que deseas eliminar esta mascota?');">
                            @csrf
                            @method('DELETE')

                            <button class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                     viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M9 2a1 1 0 00-.894.553L7.382 4H3a1 1 0 000 2h1v10a2 2 0 002 2h8a2 2 0 002-2V6h1a1 1 0 100-2h-4.382l-.724-1.447A1 1 0 0011 2H9zm3 6a1 1 0 10-2 0v6a1 1 0 102 0V8zM9 8a1 1 0 10-2 0v6a1 1 0 102 0V8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </form>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
