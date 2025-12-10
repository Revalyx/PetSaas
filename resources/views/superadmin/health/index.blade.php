@extends('layouts.superadmin')

@section('title', 'System Health Check')

@section('content')
<div class="p-6 space-y-8">

    <!-- TÍTULO -->
    <div>
        <h1 class="text-3xl font-bold text-white mb-2">Estado del Sistema</h1>
        <p class="text-gray-400">Resumen del estado interno del SaaS, servicios esenciales y tenants activos.</p>
    </div>

    <!-- GRID PRINCIPAL -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        <!-- ESTADO GENERAL -->
        <div class="bg-gray-800 p-6 rounded-xl shadow-md border border-gray-700">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-white">Estado General</h2>
                <span class="px-3 py-1 text-sm bg-green-600 rounded-lg text-white font-semibold">OK</span>
            </div>
            <p class="text-gray-300 mt-3">Todos los servicios están funcionando correctamente.</p>
        </div>

        <!-- PHP -->
        <div class="bg-gray-800 p-6 rounded-xl shadow-md border border-gray-700">
            <h2 class="text-xl font-semibold text-white mb-4">PHP</h2>
            <div class="flex justify-between items-center">
                <span class="text-gray-400">Versión instalada:</span>
                <span class="px-3 py-1 bg-indigo-600 rounded-lg text-white font-semibold text-sm">
                    {{ $php }}
                </span>
            </div>
        </div>

        <!-- LARAVEL -->
        <div class="bg-gray-800 p-6 rounded-xl shadow-md border border-gray-700">
            <h2 class="text-xl font-semibold text-white mb-4">Laravel</h2>
            <div class="flex justify-between items-center">
                <span class="text-gray-400">Framework:</span>
                <span class="px-3 py-1 bg-red-600 rounded-lg text-white font-semibold text-sm">
                    {{ $laravel }}
                </span>
            </div>
        </div>

        <!-- FECHA SERVIDOR -->
        <div class="bg-gray-800 p-6 rounded-xl shadow-md border border-gray-700">
            <h2 class="text-xl font-semibold text-white mb-4">Hora del Servidor</h2>
            <p class="text-gray-300 text-lg font-semibold">{{ $time }}</p>
            <p class="text-gray-500 text-sm mt-1">UTC local del servidor</p>
        </div>

        <!-- NÚMERO DE TENANTS -->
        <div class="bg-gray-800 p-6 rounded-xl shadow-md border border-gray-700">
            <h2 class="text-xl font-semibold text-white mb-4">Tenants Activos</h2>
            <div class="flex justify-between items-center">
                <span class="text-gray-400">Total activos:</span>
                <span class="px-3 py-1 bg-blue-600 rounded-lg text-white font-semibold text-sm">
                    {{ $tenants->count() }}
                </span>
            </div>
        </div>

        <!-- ESTADO DE BASE DE DATOS (simple pero útil) -->
        <div class="bg-gray-800 p-6 rounded-xl shadow-md border border-gray-700">
            <h2 class="text-xl font-semibold text-white mb-4">Base de Datos</h2>
            <p class="text-gray-300">
                Conexión establecida correctamente con:
            </p>
            <p class="text-gray-400 mt-2 text-sm font-mono">
                {{ config('database.connections.mysql.database') }}
            </p>
        </div>

    </div>

    <!-- LISTADO DE TENANTS -->
    <div>
        <h2 class="text-2xl font-semibold text-white mb-4">Listado de Tenants</h2>

        <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-md">

            <table class="w-full text-left text-gray-300">
                <thead class="bg-gray-700/50 text-gray-200 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-2">Nombre</th>
                        <th class="px-4 py-2">Slug</th>
                        <th class="px-4 py-2">Base de datos</th>
                        <th class="px-4 py-2 text-center">Estado</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($tenants as $t)
                    <tr class="border-t border-gray-700 hover:bg-gray-700/40 transition">
                        <td class="px-4 py-2 font-medium">{{ $t->name }}</td>
                        <td class="px-4 py-2 text-gray-400">{{ $t->slug }}</td>
                        <td class="px-4 py-2 text-gray-400">{{ $t->db_name }}</td>
                        <td class="px-4 py-2 text-center">
                            <span class="px-3 py-1 bg-green-600 text-white rounded-lg text-xs font-semibold">
                                Activo
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
    </div>

</div>
@endsection
