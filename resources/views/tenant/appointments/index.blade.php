@extends('layouts.tenant')

@section('title', 'Citas')

@section('content')

<div class="bg-white dark:bg-slate-800
            p-8 rounded-2xl shadow-xl
            border border-slate-200 dark:border-slate-700">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
        <h2 class="text-3xl font-extrabold text-slate-800 dark:text-slate-100">
            Citas de Peluquería
        </h2>

        <a href="{{ route('tenant.appointments.create') }}"
           class="inline-flex items-center gap-2
                  px-4 py-2 rounded-xl
                  bg-teal-600 hover:bg-teal-700
                  text-white font-semibold shadow transition">
            ➕ Nueva cita
        </a>
    </div>

    {{-- TABLA --}}
    <div class="overflow-x-auto rounded-xl
                border border-slate-200 dark:border-slate-700">

        <table class="w-full text-sm text-left
                      bg-white dark:bg-slate-800">

            {{-- CABECERA --}}
            <thead class="bg-slate-100 dark:bg-slate-700
                          text-slate-600 dark:text-slate-300
                          uppercase text-xs tracking-wider">
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
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">

            @foreach($appointments as $a)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/40 transition">

                    {{-- FECHA --}}
                    <td class="px-4 py-3 font-medium text-slate-800 dark:text-slate-100">
                        {{ $a->date->format('d/m/Y') }}
                    </td>

                    {{-- HORA --}}
                    <td class="px-4 py-3 text-slate-600 dark:text-slate-300">
                        {{ $a->start_time ?: '—' }}
                        @if($a->end_time)
                            – {{ $a->end_time }}
                        @endif
                    </td>

                    {{-- CLIENTE --}}
                    <td class="px-4 py-3">
                        {{ $a->customer?->nombre ?? '—' }}
                        {{ $a->customer?->apellidos ?? '' }}
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
                            'cancelled' => 'bg-blue-600',
                            'dificiles' => 'bg-slate-500',
                        ];
                    @endphp

                    <td class="px-4 py-3">
                        <span class="inline-flex items-center
                                     px-3 py-1 rounded-full
                                     text-xs font-semibold text-white
                                     {{ $typeColors[$a->type] ?? 'bg-slate-500' }}">
                            {{ ucfirst($a->type) }}
                        </span>
                    </td>

                    {{-- ESTADO --}}
                    @php
                        $labels = [
                            'pending'   => 'Pendiente',
                            'confirmed' => 'Confirmada',
                            'completed' => 'Completada',
                            'cancelled' => 'Cancelada',
                        ];

                        $colors = [
                            'pending'   => 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-300',
                            'confirmed' => 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-300',
                            'completed' => 'bg-green-600 text-white',
                            'cancelled' => 'bg-blue-600 text-white',
                        ];

                        $estado = $a->status ?? 'pending';
                    @endphp

                    <td class="px-4 py-3">
                        <span class="inline-flex px-3 py-1 rounded-full
                                     text-xs font-semibold
                                     {{ $colors[$estado] ?? 'bg-slate-400 text-white' }}">
                            {{ $labels[$estado] ?? ucfirst($estado) }}
                        </span>
                    </td>

                    {{-- ACCIONES --}}
                    <td class="px-4 py-3">
                        <div class="flex justify-center gap-2">

                            <a href="{{ route('tenant.appointments.edit', $a->id) }}"
                               class="w-9 h-9 flex items-center justify-center
                                      rounded-lg bg-indigo-600 hover:bg-indigo-700
                                      text-white transition shadow">
                                ✏️
                            </a>

                            <button
                                onclick="openDeleteModal({{ $a->id }})"
                                class="w-9 h-9 flex items-center justify-center
                                       rounded-lg bg-red-600 hover:bg-red-700
                                       text-white transition shadow">
                                🗑️
                            </button>

                        </div>
                    </td>

                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
</div>

{{-- MODAL DELETE --}}
<div id="modal-delete"
     class="fixed inset-0 bg-black/60 backdrop-blur-sm
            hidden justify-center items-center z-50">

    <div class="bg-white dark:bg-slate-800
                text-slate-800 dark:text-slate-100
                p-6 rounded-2xl shadow-xl w-full max-w-md">

        <h2 class="text-xl font-bold mb-3">
            Confirmar eliminación
        </h2>

        <p class="text-slate-600 dark:text-slate-300 mb-6">
            ¿Seguro que deseas eliminar esta cita?
        </p>

        <div class="flex justify-end gap-3">

            <button onclick="closeDeleteModal()"
                    class="px-4 py-2 rounded-xl
                           bg-slate-200 hover:bg-slate-300
                           dark:bg-slate-700 dark:hover:bg-slate-600
                           transition">
                Cancelar
            </button>

            <form method="POST" id="delete-form">
                @csrf
                @method('DELETE')
                <button
                    class="px-4 py-2 rounded-xl
                           bg-red-600 hover:bg-red-700
                           text-white font-semibold transition">
                    Eliminar
                </button>
            </form>

        </div>
    </div>
</div>

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
