@extends('layouts.tenant')

@section('title', 'Citas')

@section('content')

<div class="bg-gray-900/20 dark:bg-gray-800/20 p-8 rounded-xl shadow-xl">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Citas de Peluquería</h2>

        <a href="{{ route('tenant.appointments.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Nueva Cita
        </a>
    </div>

    <table class="w-full text-left bg-gray-900/10 dark:bg-gray-800/10 rounded-xl overflow-hidden">
        <thead class="bg-gray-800 text-gray-200">
            <tr>
                <th class="px-4 py-3">Fecha</th>
                <th class="px-4 py-3">Hora</th>
                <th class="px-4 py-3">Cliente</th>
                <th class="px-4 py-3">Mascota</th>
                <th class="px-4 py-3">Estado</th>
                <th class="px-4 py-3">Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach($appointments as $a)
                <tr class="border-b border-gray-700/40 dark:border-gray-700/60">
                    
                    {{-- FECHA --}}
                    <td class="px-4 py-3">
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
                        {{ $a->customer?->nombre ?? '—' }} 
                        {{ $a->customer?->apellidos ?? '' }}
                    </td>

                    {{-- MASCOTA --}}
                    <td class="px-4 py-3">
                        {{ $a->pet?->nombre ?? '—' }}
                    </td>

                    {{-- ESTADO --}}
<td class="px-4 py-3">

    @php
        $labels = [
            'pending'   => 'Pendiente',
            'confirmed' => 'Confirmada',
            'cancelled' => 'Cancelada',
            'completed' => 'Completada',
        ];

        $colors = [
            'pending'   => 'bg-gray-500',
            'confirmed' => 'bg-blue-600',
            'cancelled' => 'bg-red-600',
            'completed' => 'bg-green-600',
        ];

        $estado = $a->status ?? 'pending';
    @endphp

    <span class="px-3 py-1 text-white rounded-lg {{ $colors[$estado] ?? 'bg-gray-500' }}">
        {{ $labels[$estado] ?? $estado }}
    </span>

</td>


                    {{-- ACCIONES --}}
                    <td class="px-4 py-3 flex gap-3">
                        <a href="{{ route('tenant.appointments.edit', $a->id) }}"
                           class="px-3 py-1 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                            Editar
                        </a>

                        <form method="POST" action="{{ route('tenant.appointments.destroy', $a->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                Eliminar
                            </button>
                        </form>
                    </td>

                </tr>
            @endforeach
        </tbody>

    </table>
</div>

@endsection
