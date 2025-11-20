@extends('layouts.tenant')

@section('content')
<div class="p-6">

    <h1 class="text-2xl font-semibold mb-6">Añadir cliente</h1>

    <form action="{{ route('tenant.clientes.store') }}" method="POST"
          class="bg-white dark:bg-gray-800 rounded shadow p-6">
        @csrf

        <div class="mb-4">
            <label class="block mb-1">Nombre *</label>
            <input type="text" name="nombre" required
                   class="w-full p-2 rounded bg-gray-100 dark:bg-gray-900">
        </div>

        <div class="mb-4">
            <label class="block mb-1">Apellidos *</label>
            <input type="text" name="apellidos" required
                   class="w-full p-2 rounded bg-gray-100 dark:bg-gray-900">
        </div>

        <div class="mb-4">
            <label class="block mb-1">Teléfono</label>
            <input type="text" name="telefono"
                   class="w-full p-2 rounded bg-gray-100 dark:bg-gray-900">
        </div>

        <div class="mb-4">
            <label class="block mb-1">Email</label>
            <input type="email" name="email"
                   class="w-full p-2 rounded bg-gray-100 dark:bg-gray-900">
        </div>

        <div class="mb-4">
            <label class="block mb-1">Dirección</label>
            <input type="text" name="direccion"
                   class="w-full p-2 rounded bg-gray-100 dark:bg-gray-900">
        </div>

        <div class="mb-4">
            <label class="block mb-1">Notas</label>
            <textarea name="notas" rows="3"
                      class="w-full p-2 rounded bg-gray-100 dark:bg-gray-900"></textarea>
        </div>

        <button type="submit"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Guardar
        </button>
    </form>
</div>
@endsection
