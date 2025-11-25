@extends('layouts.tenant')

@section('title', 'Editar cita')
@section('hide_header', true)

@section('content')

<div class="flex justify-center py-8">

    <div class="bg-gray-900/30 dark:bg-gray-800/40 p-8 rounded-xl shadow-xl w-full max-w-3xl">

        <h2 class="text-2xl font-bold mb-6 text-center">Editar cita</h2>

        {{-- DATOS ACTUALES --}}
        <div class="bg-gray-800/50 text-gray-200 p-4 rounded-lg mb-6">

            <p><strong>Cliente:</strong> 
                {{ $appointment->customer?->nombre }} {{ $appointment->customer?->apellidos }}
            </p>

            <p><strong>Mascota:</strong> 
                {{ $appointment->pet?->nombre ?? '—' }}
            </p>

            <p><strong>Fecha:</strong> {{ $appointment->date->format('d/m/Y') }}</p>

            <p><strong>Hora:</strong>
                {{ $appointment->start_time ?: '--' }}
                @if($appointment->end_time)
                    – {{ $appointment->end_time }}
                @endif
            </p>

            <p><strong>Estado:</strong> {{ ucfirst($appointment->status) }}</p>

        </div>

        <form method="POST" action="{{ route('tenant.appointments.update', $appointment->id) }}" class="grid grid-cols-2 gap-6">
            @csrf
            @method('PUT')

            {{-- CLIENTE --}}
            <div class="col-span-2">
                <label class="font-semibold">Cliente</label>
                <select name="customer_id" class="w-full bg-gray-700 rounded-lg px-3 py-2">
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}" {{ $c->id == $appointment->customer_id ? 'selected' : '' }}>
                            {{ $c->nombre }} {{ $c->apellidos }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- MASCOTA --}}
            <div class="col-span-2">
                <label class="font-semibold">Mascota (opcional)</label>
                <select name="pet_id" class="w-full bg-gray-700 rounded-lg px-3 py-2">
                    <option value="">Sin mascota</option>
                    @foreach($pets as $p)
                        <option value="{{ $p->id }}" {{ $p->id == $appointment->pet_id ? 'selected' : '' }}>
                            {{ $p->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- FECHA --}}
            <div>
                <label class="font-semibold">Fecha</label>
                <input type="date" name="date"
                       value="{{ $appointment->date->format('Y-m-d') }}"
                       class="w-full bg-gray-700 rounded-lg px-3 py-2">
            </div>

            {{-- HORA INICIO --}}
            <div>
                <label class="font-semibold">Hora inicio</label>
                <input type="time" name="start_time"
                       value="{{ $appointment->start_time }}"
                       class="w-full bg-gray-700 rounded-lg px-3 py-2">
            </div>

            {{-- HORA FIN --}}
            <div>
                <label class="font-semibold">Hora fin</label>
                <input type="time" name="end_time"
                       value="{{ $appointment->end_time }}"
                       class="w-full bg-gray-700 rounded-lg px-3 py-2">
            </div>

            {{-- ESTADO --}}
            <div>
                <label class="font-semibold">Estado</label>
                <select name="status" class="w-full bg-gray-700 rounded-lg px-3 py-2">
                    <option value="pending" {{ $appointment->status=='pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="confirmed" {{ $appointment->status=='confirmed' ? 'selected' : '' }}>Confirmada</option>
                    <option value="cancelled" {{ $appointment->status=='cancelled' ? 'selected' : '' }}>Cancelada</option>
                    <option value="completed" {{ $appointment->status=='completed' ? 'selected' : '' }}>Completada</option>
                </select>
            </div>

            {{-- NOTAS --}}
            <div class="col-span-2">
                <label class="font-semibold">Notas</label>
                <textarea name="notes" rows="3" class="w-full bg-gray-700 rounded-lg px-3 py-2">
                    {{ $appointment->notes }}
                </textarea>
            </div>

            {{-- BOTONES --}}
            <div class="col-span-2 flex justify-end gap-3">
                <a href="{{ route('tenant.appointments.index') }}"
                    class="px-4 py-2 bg-gray-600 text-white rounded-lg">Cancelar</a>

                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Guardar cambios
                </button>
            </div>

        </form>

    </div>

</div>

@endsection
