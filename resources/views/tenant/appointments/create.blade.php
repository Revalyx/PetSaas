@extends('layouts.tenant')

@section('title', 'Nueva Cita')

@section('content')

@php
    $input = '
        w-full rounded-xl px-3 py-2 border
        bg-white text-slate-900 border-slate-300
        dark:bg-slate-900 dark:text-slate-100 dark:border-slate-600
        focus:ring-2 focus:ring-teal-500 focus:outline-none
    ';

    $label = 'block font-semibold mb-1 text-slate-700 dark:text-slate-200';
@endphp

<div class="w-full flex justify-center mt-6">

    <div class="bg-white dark:bg-slate-800
                p-8 rounded-2xl shadow-xl
                w-full max-w-2xl
                border border-slate-200 dark:border-slate-700">

        <h2 class="text-2xl font-extrabold mb-6 text-center
                   text-slate-800 dark:text-slate-100">
            Crear nueva cita
        </h2>

        <form method="POST"
              action="{{ route('tenant.appointments.store') }}"
              class="space-y-6 text-sm">
            @csrf

            {{-- CLIENTE --}}
            <div>
                <label class="{{ $label }}">Cliente</label>
                <select id="customerSelect"
                        name="customer_id"
                        class="{{ $input }}"
                        required>
                    <option value="">Seleccione un cliente...</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}">
                            {{ $c->nombre }} {{ $c->apellidos }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- MASCOTA --}}
            <div>
                <label class="{{ $label }}">Mascota</label>
                <select id="petSelect"
                        name="pet_id"
                        class="{{ $input }}"
                        disabled>
                    <option value="">Seleccione primero un cliente</option>
                </select>
            </div>

            {{-- TIPO DE CITA --}}
            <div>
                <label class="{{ $label }}">Tipo de cita</label>
                <select name="type"
                        class="{{ $input }}"
                        required>
                    <option value="muda">Muda</option>
                    <option value="corte">Corte</option>
                    <option value="arreglo">Arreglo</option>
                    <option value="gato">Gato</option>
                    <option value="cancelled">Cancelar</option>
                </select>
            </div>

            {{-- CITA DIFÍCIL --}}
            <div class="flex items-center gap-3">
                <input type="checkbox"
                       name="is_difficult"
                       value="1"
                       class="w-4 h-4 rounded
                              text-teal-600
                              bg-white dark:bg-slate-900
                              border-slate-400 dark:border-slate-600
                              focus:ring-teal-500">
                <label class="font-semibold text-slate-700 dark:text-slate-200">
                    Marcar como cita difícil
                </label>
            </div>

            {{-- FECHA --}}
            <div>
                <label class="{{ $label }}">Fecha</label>
                <input type="date"
                       name="date"
                       value="{{ old('date', $prefillDate ?? '') }}"
                       class="{{ $input }}"
                       required>
            </div>

            {{-- HORA INICIO --}}
            <div>
                <label class="{{ $label }}">Hora inicio</label>
                <input type="time"
                       name="start_time"
                       class="{{ $input }}"
                       required>
            </div>

            {{-- HORA FIN --}}
            <div>
                <label class="{{ $label }}">Hora fin (opcional)</label>
                <input type="time"
                       name="end_time"
                       class="{{ $input }}">
            </div>

            {{-- ESTADO --}}
            <div>
                <label class="{{ $label }}">Estado</label>
                <select name="status"
                        class="{{ $input }}">
                    <option value="pending">Pendiente</option>
                    <option value="confirmed">Confirmada</option>
                    <option value="cancelled">Cancelada</option>
                    <option value="completed">Completada</option>
                </select>
            </div>

            {{-- NOTAS --}}
            <div>
                <label class="{{ $label }}">Notas</label>
                <textarea name="notes"
                          rows="3"
                          class="{{ $input }}"
                          placeholder="Detalles adicionales..."></textarea>
            </div>

            {{-- BOTONES --}}
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('tenant.appointments.index') }}"
                   class="px-4 py-2 rounded-xl
                          bg-slate-200 hover:bg-slate-300
                          dark:bg-slate-700 dark:hover:bg-slate-600
                          text-slate-800 dark:text-slate-100
                          transition">
                    Cancelar
                </a>

                <button type="submit"
                        class="px-5 py-2 rounded-xl
                               bg-teal-600 hover:bg-teal-700
                               text-white font-semibold
                               transition shadow">
                    Guardar
                </button>
            </div>

        </form>

    </div>

</div>

{{-- =========================
     JS: CARGA DE MASCOTAS
========================= --}}
<script>
document.getElementById('customerSelect').addEventListener('change', function () {

    const clientId = this.value;
    const petSelect = document.getElementById('petSelect');

    petSelect.disabled = true;
    petSelect.innerHTML = '<option>Cargando mascotas...</option>';

    if (!clientId) {
        petSelect.innerHTML = '<option>Seleccione primero un cliente</option>';
        return;
    }

    fetch(`/tenant/clients/${clientId}/pets`)
        .then(res => res.json())
        .then(pets => {

            petSelect.innerHTML = '<option value="">Sin mascota</option>';
            petSelect.disabled = false;

            if (pets.length === 0) {
                const opt = document.createElement('option');
                opt.disabled = true;
                opt.textContent = '(Este cliente no tiene mascotas)';
                petSelect.appendChild(opt);
                return;
            }

            pets.forEach(pet => {
                const opt = document.createElement('option');
                opt.value = pet.id;
                opt.textContent = pet.nombre;
                petSelect.appendChild(opt);
            });
        })
        .catch(() => {
            petSelect.innerHTML = '<option>Error al cargar mascotas</option>';
        });
});
</script>

@endsection
