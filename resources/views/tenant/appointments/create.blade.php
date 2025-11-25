@extends('layouts.tenant')

@section('title', 'Nueva Cita')

@section('content')

<div class="w-full flex justify-center mt-6">

    <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-xl w-full max-w-full">


        <h2 class="text-xl font-bold mb-4 text-center">Crear nueva cita</h2>

        <form method="POST" action="{{ route('tenant.appointments.store') }}" class="grid grid-cols-2 gap-3 text-sm">
            @csrf

            {{-- CLIENTE --}}
            <div class="col-span-2">
                <label class="block font-semibold mb-1">Cliente</label>
                <select name="customer_id"
                    class="w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 
                           rounded-lg px-2 py-1"
                    required>
                    <option value="">Seleccione un cliente...</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}">{{ $c->nombre }} {{ $c->apellidos }}</option>
                    @endforeach
                </select>
            </div>

            {{-- MASCOTA --}}
            <div class="col-span-2">
                <label class="block font-semibold mb-1">Mascota (opcional)</label>
                <select name="pet_id"
                    class="w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 
                           rounded-lg px-2 py-1">
                    <option value="">Sin mascota</option>
                    @foreach($pets as $p)
                        <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                    @endforeach
                </select>
            </div>

            {{-- FECHA --}}
            <div>
                <label class="block font-semibold mb-1">Fecha</label>
                <input type="date" name="date" value="{{ old('date', isset($prefillDate) ? $prefillDate : '') }}" ...>

            </div>

            {{-- HORA INICIO --}}
            <div>
                <label class="block font-semibold mb-1">Hora inicio</label>
                <input type="time" name="start_time"
                    class="w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 
                           rounded-lg px-2 py-1"
                    required>
            </div>

            {{-- HORA FIN --}}
            <div>
                <label class="block font-semibold mb-1">Hora fin (opcional)</label>
                <input type="time" name="end_time"
                    class="w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 
                           rounded-lg px-2 py-1">
            </div>

            {{-- ESTADO --}}
            <div>
                <label class="block font-semibold mb-1">Estado</label>
                <select name="status"
                    class="w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 
                           rounded-lg px-2 py-1">
                    <option value="pending">Pendiente</option>
                    <option value="confirmed">Confirmada</option>
                    <option value="cancelled">Cancelada</option>
                    <option value="completed">Completada</option>
                </select>
            </div>

            {{-- NOTAS --}}
            <div class="col-span-2">
                <label class="block font-semibold mb-1">Notas</label>
                <textarea name="notes" rows="2"
                    class="w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 
                           rounded-lg px-2 py-1"
                    placeholder="Detalles adicionales..."></textarea>
            </div>

            {{-- BOTONES --}}
            <div class="col-span-2 flex justify-end gap-2 pt-2">
                <a href="{{ route('tenant.appointments.index') }}"
                   class="px-3 py-1 bg-gray-500 text-white text-sm rounded-lg hover:bg-gray-600">
                    Cancelar
                </a>

                <button class="px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                    Guardar
                </button>
            </div>

        </form>

    </div>

</div>

@endsection
