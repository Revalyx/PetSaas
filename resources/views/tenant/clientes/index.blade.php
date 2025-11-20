@extends('layouts.tenant')

@section('content')
<div class="p-6">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Clientes</h1>

        <a href="{{ route('tenant.clientes.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
           Añadir cliente
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded shadow p-6">
        @if($clientes->isEmpty())
            <p class="text-gray-500">No hay clientes registrados aún.</p>
        @else
            <table class="w-full text-left">
                <thead>
                    <tr>
                        <th class="pb-3">Nombre</th>
                        <th class="pb-3">Apellidos</th>
                        <th class="pb-3">Teléfono</th>
                        <th class="pb-3">Email</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clientes as $c)
                    <tr class="border-t border-gray-300 dark:border-gray-700">
                        <td class="py-2">{{ $c->nombre }}</td>
                        <td class="py-2">{{ $c->apellidos }}</td>
                        <td class="py-2">{{ $c->telefono ?? '-' }}</td>
                        <td class="py-2">{{ $c->email ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
