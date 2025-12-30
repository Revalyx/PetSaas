@extends('layouts.tenant')

@section('title', 'Editar cita')
@section('hide_header', true)

@section('content')

<div class="flex justify-center py-12 px-4">

    <div class="w-full max-w-3xl rounded-3xl
                bg-gradient-to-b from-white to-[#f1f5f9]
                dark:bg-gradient-to-b dark:from-[#0f172a] dark:to-[#020617]
                border border-[#e5e7eb] dark:border-[#1e293b]
                shadow-2xl p-10 space-y-8">

        {{-- TÍTULO --}}
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-[#14b8a6]">
                Editar cita
            </h2>
            <p class="text-sm text-[#64748b] dark:text-[#94a3b8] mt-1">
                Modifique los datos de la cita existente
            </p>
        </div>

        {{-- RESUMEN ACTUAL --}}
        <div class="rounded-2xl p-5
                    bg-white/80 dark:bg-[#020617]/70
                    border border-[#e5e7eb] dark:border-[#1e293b]
                    backdrop-blur
                    text-sm grid grid-cols-1 md:grid-cols-2 gap-2">

            <p><span class="font-semibold text-[#14b8a6]">Cliente:</span>
                {{ $appointment->customer?->nombre }} {{ $appointment->customer?->apellidos }}
            </p>

            <p><span class="font-semibold text-[#14b8a6]">Mascota:</span>
                {{ $appointment->pet?->nombre ?? '—' }}
            </p>

            <p><span class="font-semibold text-[#14b8a6]">Fecha:</span>
                {{ $appointment->date->format('d/m/Y') }}
            </p>

            <p><span class="font-semibold text-[#14b8a6]">Hora:</span>
                {{ $appointment->start_time ?: '--' }}
                @if($appointment->end_time)
                    – {{ $appointment->end_time }}
                @endif
            </p>

            <p class="md:col-span-2">
                <span class="font-semibold text-[#14b8a6]">Estado:</span>
                {{ ucfirst($appointment->status) }}
            </p>
        </div>

        {{-- FORMULARIO --}}
        <form method="POST"
              action="{{ route('tenant.appointments.update', $appointment->id) }}"
              class="space-y-6">
            @csrf
            @method('PUT')

            @php
                $inputBase = '
                    w-full rounded-xl px-4 py-2
                    bg-white dark:bg-[#020617]
                    border border-[#e5e7eb] dark:border-[#1e293b]
                    text-[#0f172a] dark:text-[#e5e7eb]
                    focus:outline-none
                    focus:ring-2 focus:ring-[#14b8a6]/50
                    transition
                ';
                $labelBase = '
                    block mb-1 font-semibold
                    text-[#0f172a] dark:text-white
                ';
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
            <div class="flex items-center gap-3 p-4 rounded-xl
                        bg-[#14b8a6]/10 dark:bg-[#14b8a6]/15">
                <input type="checkbox"
                       name="is_difficult"
                       value="1"
                       {{ $appointment->is_difficult ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-[#14b8a6] text-[#14b8a6] focus:ring-[#14b8a6]">
                <span class="text-sm text-[#0f172a] dark:text-[#e5e7eb] font-medium">
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
            <div class="flex justify-end gap-4 pt-6">
                <a href="{{ route('tenant.appointments.index') }}"
                   class="px-4 py-2 rounded-xl
                          bg-[#e5e7eb] dark:bg-[#1e293b]
                          text-[#0f172a] dark:text-[#e5e7eb]">
                    Cancelar
                </a>

                <button class="px-6 py-2 rounded-xl
                               bg-gradient-to-r from-[#14b8a6] to-[#0d9488]
                               hover:brightness-110
                               text-[#0f172a]
                               font-semibold shadow-lg">
                    Guardar cambios
                </button>
            </div>

        </form>

    </div>

</div>

@endsection
