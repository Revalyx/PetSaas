@extends('layouts.superadmin')

@section('title', 'Dashboard Superadmin')

@section('content')

<div class="space-y-12">

    <!-- ========================================================
         TARJETAS KPI — Versión Premium
    ========================================================== -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        <!-- CARD: Empresas -->
        <div class="relative overflow-hidden rounded-3xl shadow-lg 
                    bg-gradient-to-br from-orange-500 to-orange-600 
                    text-white p-7 transform hover:scale-[1.02] transition-all">

            <div class="absolute top-0 right-0 opacity-20 text-7xl">🏢</div>

            <p class="text-sm uppercase tracking-wide opacity-80 font-semibold">
                Empresas totales
            </p>
            <p class="text-6xl font-extrabold mt-1 drop-shadow-md">
                {{ $total_tenants }}
            </p>
        </div>

        <!-- CARD: Usuarios -->
        <div class="relative overflow-hidden rounded-3xl shadow-lg 
                    bg-gradient-to-br from-purple-500 to-purple-600 
                    text-white p-7 transform hover:scale-[1.02] transition-all">

            <div class="absolute top-0 right-0 opacity-20 text-7xl">👥</div>

            <p class="text-sm uppercase tracking-wide opacity-80 font-semibold">
                Usuarios totales
            </p>
            <p class="text-6xl font-extrabold mt-1 drop-shadow-md">
                {{ $users }}
            </p>
        </div>

        <!-- CARD: Ratio -->
        <div class="relative overflow-hidden rounded-3xl shadow-lg 
                    bg-gradient-to-br from-teal-500 to-teal-600 
                    text-white p-7 transform hover:scale-[1.02] transition-all">

            <div class="absolute top-0 right-0 opacity-20 text-7xl">📊</div>

            <p class="text-sm uppercase tracking-wide opacity-80 font-semibold">
                Usuarios por empresa
            </p>
            <p class="text-6xl font-extrabold mt-1 drop-shadow-md">
                @if($total_tenants > 0)
                    {{ number_format($users / $total_tenants, 1) }}
                @else
                    —
                @endif
            </p>
        </div>

    </div>




    <!-- ========================================================
         TABLA DE EMPRESAS — Versión SaaS Pro
    ========================================================== -->
    <div class="bg-white/10 dark:bg-white/5 backdrop-blur-xl rounded-3xl shadow-xl border border-white/10 p-10">

        <h2 class="text-2xl font-bold text-gray-100 mb-8">Empresas Registradas</h2>

        @if($tenants->count() === 0)
            <p class="text-gray-400 text-sm">Aún no hay empresas creadas.</p>
        @else

        <div class="overflow-x-auto rounded-2xl border border-white/10">
            <table class="min-w-full text-left text-sm text-gray-300">
                <thead class="bg-white/10 text-gray-200 uppercase text-xs">
                    <tr>
                        <th class="py-4 px-4">Nombre</th>
                        <th class="py-4 px-4">Slug</th>
                        <th class="py-4 px-4">Base de Datos</th>
                        <th class="py-4 px-4">Fecha</th>
                        <th class="py-4 px-4 text-right">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-white/5 backdrop-blur-xl">

                    @foreach ($tenants as $tenant)
                        <tr class="hover:bg-white/5 transition-all">
                            <td class="py-4 px-4 font-semibold">{{ $tenant->name }}</td>
                            <td class="py-4 px-4 text-gray-400">{{ $tenant->slug }}</td>
                            <td class="py-4 px-4 text-gray-400">{{ $tenant->db_name }}</td>
                            <td class="py-4 px-4 text-gray-400">
                                {{ $tenant->created_at->format('d/m/Y') }}
                            </td>

                            <td class="py-4 px-4 text-right">
                                <button onclick="openDeleteModal({{ $tenant->id }}, '{{ $tenant->name }}')"
                                        class="px-4 py-2 rounded-xl text-white font-semibold
                                               bg-red-600 hover:bg-red-700 
                                               shadow-md hover:shadow-lg transition">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        @endif

    </div>

</div>

@include('superadmin.tenants._delete_modal')

@endsection
