@extends('layouts.tenant')

@section('title', 'Dashboard')

@section('content')

<div class="space-y-10">

    <!-- KPIs de la empresa -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        <div class="p-6 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-lg">
            <p class="text-sm opacity-80">Clientes</p>
            <p class="text-5xl font-bold mt-1">—</p>
        </div>

        <div class="p-6 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 text-white shadow-lg">
            <p class="text-sm opacity-80">Mascotas</p>
            <p class="text-5xl font-bold mt-1">—</p>
        </div>

        <div class="p-6 rounded-2xl bg-gradient-to-br from-teal-500 to-teal-600 text-white shadow-lg">
            <p class="text-sm opacity-80">Citas hoy</p>
            <p class="text-5xl font-bold mt-1">—</p>
        </div>

        <a href="{{ route('tenant.clientes.index') }}"
   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
   Gestionar Clientes
</a>


    </div>


    <!-- Sección inferior -->
    <div class="bg-white/10 dark:bg-white/5 p-8 rounded-3xl shadow-xl border border-white/10">
        <h2 class="text-xl font-bold mb-4">Resumen general</h2>

        <p class="text-gray-300">
            Aquí podrás ver estadísticas y gestión relacionada exclusivamente con tu empresa.
        </p>
    </div>

</div>

@endsection
