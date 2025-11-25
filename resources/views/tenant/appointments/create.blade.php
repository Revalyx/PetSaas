@extends('layouts.tenant')

@section('title', 'Nueva Cita')

@section('content')

<div class="w-full flex justify-center mt-6">

    <div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-xl w-full max-w-2xl">

        <h2 class="text-2xl font-bold mb-6 text-center text-white">Crear nueva cita</h2>

        <form method="POST" action="{{ route('tenant.appointments.store') }}" class="space-y-6 text-sm">
            @csrf

            {{-- CLIENTE --}}
            <div>
                <label class="block font-semibold mb-1 text-white">Cliente</label>
                <select name="customer_id"
                    class="w-full bg-gray-100 dark:bg-gray-700 border border-gray-600 rounded-lg px-3 py-2"
                    required>
                    <option value="">Seleccione un cliente...</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}">{{ $c->nombre }} {{ $c->apellidos }}</option>
                    @endforeach
                </select>
            </div>

            {{-- MASCOTA --}}
            <div>
                <label class="block font-semibold mb-1 text-white">Mascota (opcional)</label>
                <select name="pet_id"
                    class="w-full bg-gray-100 dark:bg-gray-700 border border-gray-600 rounded-lg px-3 py-2">
                    <option value="">Sin mascota</option>
                    @foreach($pets as $p)
                        <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                    @endforeach
                </select>
            </div>

            {{-- TIPO DE CITA --}}
            <div>
                <label class="block font-semibold mb-1 text-white">Tipo de cita</label>
                <select name="type"
                        class="w-full bg-gray-100 dark:bg-gray-700 border border-gray-600 rounded-lg px-3 py-2"
                        required>
                    <option value="muda">Muda</option>
                    <option value="corte">Corte</option>
                    <option value="arreglo">Arreglo</option>
                    <option value="gato">Gato</option>
                    <option value="cancelar">Cancelar</option>
                </select>
            </div>

            {{-- CITA DIFÍCIL --}}
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_difficult" value="1"
                       class="w-4 h-4 bg-gray-700 border-gray-500 rounded">
                <label class="font-semibold text-white">Marcar como cita difícil</label>
            </div>

            {{-- FECHA --}}
            <div>
                <label class="block font-semibold mb-1 text-white">Fecha</label>
                <input type="date" name="date"
                       value="{{ old('date', $prefillDate ?? '') }}"
                       class="w-full bg-gray-100 dark:bg-gray-700 border border-gray-600 rounded-lg px-3 py-2"
                       required>
            </div>

            {{-- HORA INICIO --}}
            <div>
                <label class="block font-semibold mb-1 text-white">Hora inicio</label>
                <input type="time" name="start_time"
                       class="w-full bg-gray-100 dark:bg-gray-700 border border-gray-600 rounded-lg px-3 py-2"
                       required>
            </div>

            {{-- HORA FIN --}}
            <div>
                <label class="block font-semibold mb-1 text-white">Hora fin (opcional)</label>
                <input type="time" name="end_time"
                       class="w-full bg-gray-100 dark:bg-gray-700 border border-gray-600 rounded-lg px-3 py-2">
            </div>

            {{-- ESTADO --}}
            <div>
                <label class="block font-semibold mb-1 text-white">Estado</label>
                <select name="status"
                        class="w-full bg-gray-100 dark:bg-gray-700 border border-gray-600 rounded-lg px-3 py-2">
                    <option value="pending">Pendiente</option>
                    <option value="confirmed">Confirmada</option>
                    <option value="cancelled">Cancelada</option>
                    <option value="completed">Completada</option>
                </select>
            </div>

            {{-- NOTAS --}}
            <div>
                <label class="block font-semibold mb-1 text-white">Notas</label>
                <textarea name="notes" rows="3"
                    class="w-full bg-gray-100 dark:bg-gray-700 border border-gray-600 rounded-lg px-3 py-2"
                    placeholder="Detalles adicionales..."></textarea>
            </div>

            {{-- BOTONES --}}
            <div class="flex justify-end gap-3 pt-3">
                <a href="{{ route('tenant.appointments.index') }}"
                   class="px-4 py-2 bg-gray-600 text-white rounded-lg">Cancelar</a>

                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Guardar
                </button>
            </div>

        </form>

    </div>

</div>

@endsection
