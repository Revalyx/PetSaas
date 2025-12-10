@extends('layouts.tenant')

@section('title', 'Citas')

@section('content')

<div class="bg-gray-900/20 dark:bg-gray-800/20 p-8 rounded-xl shadow-xl">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-white">Citas de Peluquería</h2>

        <a href="{{ route('tenant.appointments.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-md transition">
            ➕ Nueva Cita
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-700/50 shadow-xl">
        <table class="w-full text-left bg-gray-900/10 dark:bg-gray-800/10">
            
            {{-- CABECERA --}}
            <thead class="bg-gray-800 text-gray-200 uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-4 py-3">Fecha</th>
                    <th class="px-4 py-3">Hora</th>
                    <th class="px-4 py-3">Cliente</th>
                    <th class="px-4 py-3">Mascota</th>
                    <th class="px-4 py-3">Tipo</th>
                    <th class="px-4 py-3">Estado</th>
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>

            {{-- CUERPO --}}
            <tbody class="text-white text-sm">
                @foreach($appointments as $a)
                    <tr class="border-b border-gray-700/40 hover:bg-gray-700/20 transition">

                        {{-- FECHA --}}
                        <td class="px-4 py-3 font-medium">
                            {{ $a->date->format('d/m/Y') }}
                        </td>

                        {{-- HORA --}}
                        <td class="px-4 py-3">
                            {{ $a->start_time ?: '—' }}
                            @if($a->end_time)
                                - {{ $a->end_time }}
                            @endif
                        </td>

                        {{-- CLIENTE --}}
                        <td class="px-4 py-3">
                            {{ $a->customer?->nombre ?? '—' }} {{ $a->customer?->apellidos ?? '' }}
                        </td>

                        {{-- MASCOTA --}}
                        <td class="px-4 py-3">
                            {{ $a->pet?->nombre ?? '—' }}
                        </td>

                        {{-- TIPO --}}
                        @php
                            $typeColors = [
                                'muda'      => 'bg-green-600',
                                'corte'     => 'bg-red-600',
                                'arreglo'   => 'bg-pink-500',
                                'gato'      => 'bg-yellow-400 text-black',
                                'cancelar'  => 'bg-blue-600',
                                'dificiles' => 'bg-gray-500',
                            ];
                        @endphp

                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-lg text-xs font-semibold {{ $typeColors[$a->type] ?? 'bg-gray-600' }}">
                                {{ ucfirst($a->type) }}
                            </span>
                        </td>

                        {{-- ESTADO --}}
                        @php
                            $labels = [
                                'pending'   => 'Pendiente',
                                'confirmed' => 'Confirmada',
                                'cancelled' => 'Cancelada',
                                'completed' => 'Completada',
                            ];

                            $colors = [
                                'pending'   => 'bg-transparent text-gray-300 border border-gray-600',
                                'confirmed' => 'bg-transparent text-gray-300 border border-gray-600',
                                'completed' => 'bg-transparent text-gray-300 border border-gray-600',
                                'cancelled' => 'bg-blue-600 text-white',
                            ];

                            $estado = $a->status ?? 'pending';
                        @endphp

                        <td class="px-4 py-3">
                            <span class="px-3 py-1 text-white rounded-lg text-xs font-semibold {{ $colors[$estado] ?? 'bg-gray-500' }}">
                                {{ $labels[$estado] ?? $estado }}
                            </span>
                        </td>

                        {{-- ACCIONES --}}
                        <td class="px-4 py-3 flex justify-center gap-3">

                            {{-- EDITAR --}}
                            <a href="{{ route('tenant.appointments.edit', $a->id) }}"
                               class="px-3 py-1 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow">
                                ✏️ Editar
                            </a>

                            {{-- ELIMINAR → abre modal --}}
                            <button
                                onclick="openDeleteModal({{ $a->id }})"
                                class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 transition shadow">
                                🗑️
                            </button>

                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>

</div>


{{-- ============================= --}}
{{-- MODAL DE CONFIRMACIÓN BORRADO --}}
{{-- ============================= --}}
<div id="modal-delete"
     class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden justify-center items-center z-50">
    
    <div class="bg-gray-800 text-white p-6 rounded-xl shadow-xl w-full max-w-md">

        <h2 class="text-xl font-bold mb-4">Confirmar eliminación</h2>
        <p class="text-gray-300 mb-6">¿Seguro que deseas eliminar esta cita?</p>

        <div class="flex justify-end gap-3">
            <button onclick="closeDeleteModal()"
                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded-lg">
                Cancelar
            </button>

            <form method="POST" id="delete-form">
                @csrf
                @method('DELETE')
                <button class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg">
                    Eliminar
                </button>
            </form>
        </div>

    </div>
</div>


{{-- SCRIPT MODAL --}}
<script>
    function openDeleteModal(id) {
        document.getElementById('delete-form').action =
            `/tenant/appointments/${id}`;
        document.getElementById('modal-delete').classList.remove('hidden');
        document.getElementById('modal-delete').classList.add('flex');
    }

    function closeDeleteModal() {
        document.getElementById('modal-delete').classList.add('hidden');
        document.getElementById('modal-delete').classList.remove('flex');
    }
</script>

@endsection
