@extends('layouts.tenant')

@section('title', 'Editar cita')
@section('hide_header', true)

@section('content')

<div class="flex justify-center py-10 px-4">

    <div class="w-full max-w-3xl rounded-2xl
                bg-[#f8fafc] dark:bg-gradient-to-b dark:from-[#0f172a] dark:to-[#020617]
                border border-[#e5e7eb] dark:border-[#1e293b]
                shadow-xl p-8 space-y-6">

        {{-- TÍTULO --}}
        <div class="text-center">
            <h2 class="text-3xl font-bold text-[#0f172a] dark:text-white">
                Editar cita
            </h2>
            <p class="text-sm text-[#64748b] dark:text-[#94a3b8] mt-1">
                Modifique los datos de la cita existente
            </p>
        </div>

        {{-- RESUMEN ACTUAL --}}
        <div class="rounded-xl p-4
                    bg-white dark:bg-[#020617]
                    border border-[#e5e7eb] dark:border-[#1e293b]
                    text-sm space-y-1">

            <p><strong>Cliente:</strong>
                {{ $appointment->customer?->nombre }} {{ $appointment->customer?->apellidos }}
            </p>

            <p><strong>Mascota:</strong>
                {{ $appointment->pet?->nombre ?? '—' }}
            </p>

            <p><strong>Fecha:</strong>
                {{ $appointment->date->format('d/m/Y') }}
            </p>

            <p><strong>Hora:</strong>
                {{ $appointment->start_time ?: '--' }}
                @if($appointment->end_time)
                    – {{ $appointment->end_time }}
                @endif
            </p>

            <p><strong>Estado:</strong>
                {{ ucfirst($appointment->status) }}
            </p>
        </div>

        {{-- FORMULARIO --}}
        <form method="POST"
              action="{{ route('tenant.appointments.update', $appointment->id) }}"
              class="space-y-5">
            @csrf
            @method('PUT')

            {{-- SELECTS / INPUTS --}}
            @php
                $inputBase = 'w-full rounded-xl px-4 py-2
                              bg-white dark:bg-[#020617]
                              border border-[#e5e7eb] dark:border-[#1e293b]
                              text-[#0f172a] dark:text-[#e5e7eb]
                              focus:outline-none focus:ring-2 focus:ring-[#2563eb]/40';
                $labelBase = 'block mb-1 font-semibold text-[#0f172a] dark:text-white';
            @endphp

            <div>
                <label class="{{ $labelBase }}">Cliente</label>
                <select name="customer_id" class="{{ $inputBase }}">
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}" {{ $c->id == $appointment->customer_id ? 'selected' : '' }}>
                            {{ $c->nombre }} {{ $c->apellidos }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="{{ $labelBase }}">Mascota (opcional)</label>
                <select name="pet_id" class="{{ $inputBase }}">
                    <option value="">Sin mascota</option>
                    @foreach($pets as $p)
                        <option value="{{ $p->id }}" {{ $p->id == $appointment->pet_id ? 'selected' : '' }}>
                            {{ $p->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="{{ $labelBase }}">Tipo de cita</label>
                <select name="type" class="{{ $inputBase }}">
                    <option value="muda"     {{ $appointment->type=='muda' ? 'selected' : '' }}>Muda</option>
                    <option value="corte"    {{ $appointment->type=='corte' ? 'selected' : '' }}>Corte</option>
                    <option value="arreglo"  {{ $appointment->type=='arreglo' ? 'selected' : '' }}>Arreglo</option>
                    <option value="gato"     {{ $appointment->type=='gato' ? 'selected' : '' }}>Gato</option>
                    <option value="cancelar" {{ $appointment->type=='cancelar' ? 'selected' : '' }}>Cancelar</option>
                </select>
            </div>

            {{-- DIFÍCIL --}}
            <div class="flex items-center gap-3">
                <input type="checkbox"
                       name="is_difficult"
                       value="1"
                       {{ $appointment->is_difficult ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-[#1e293b] text-[#2563eb] focus:ring-[#2563eb]">
                <span class="text-sm text-[#0f172a] dark:text-[#e5e7eb]">
                    Marcar como cita difícil
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="{{ $labelBase }}">Fecha</label>
                    <input type="date" name="date"
                           value="{{ $appointment->date->format('Y-m-d') }}"
                           class="{{ $inputBase }}">
                </div>

                <div>
                    <label class="{{ $labelBase }}">Hora inicio</label>
                    <input type="time" name="start_time"
                           value="{{ $appointment->start_time }}"
                           class="{{ $inputBase }}">
                </div>

                <div>
                    <label class="{{ $labelBase }}">Hora fin</label>
                    <input type="time" name="end_time"
                           value="{{ $appointment->end_time }}"
                           class="{{ $inputBase }}">
                </div>
            </div>

            <div>
                <label class="{{ $labelBase }}">Estado</label>
                <select name="status" class="{{ $inputBase }}">
                    <option value="pending"   {{ $appointment->status=='pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="confirmed" {{ $appointment->status=='confirmed' ? 'selected' : '' }}>Confirmada</option>
                    <option value="cancelled" {{ $appointment->status=='cancelled' ? 'selected' : '' }}>Cancelada</option>
                    <option value="completed" {{ $appointment->status=='completed' ? 'selected' : '' }}>Completada</option>
                </select>
            </div>

            <div>
                <label class="{{ $labelBase }}">Notas</label>
                <textarea name="notes" rows="3" class="{{ $inputBase }}">{{ $appointment->notes }}</textarea>
            </div>

            {{-- ACCIONES --}}
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('tenant.appointments.index') }}"
                   class="px-4 py-2 rounded-xl
                          bg-[#e5e7eb] dark:bg-[#1e293b]
                          text-[#0f172a] dark:text-[#e5e7eb]">
                    Cancelar
                </a>

                <button class="px-5 py-2 rounded-xl
                               bg-[#2563eb] hover:bg-[#1d4ed8]
                               text-white font-semibold shadow">
                    Guardar cambios
                </button>
            </div>

        </form>

    </div>

</div>

@endsection
