@extends('layouts.superadmin')

@section('title', 'Crear Usuario')

@section('content')

<!-- ============================================================= -->
<!--  ESTILOS NATIVOS PARA <OPTION> (EVITA QUE SE VEAN INVISIBLES) -->
<!-- ============================================================= -->
<style>
    /* Opciones visibles en modo claro */
    select option {
        color: #111827; /* text-gray-900 */
        background: #ffffff;
    }

    /* Opciones visibles en modo oscuro */
    html.dark select option {
        color: #f3f4f6 !important; /* text-gray-100 */
        background: #1f2937 !important; /* bg-gray-800 */
    }

    /* Placeholder */
    select:invalid {
        color: #9ca3af; /* text-gray-400 */
    }
    html.dark select:invalid {
        color: #d1d5db;
    }
</style>

<h1 class="text-4xl font-extrabold text-gray-800 dark:text-gray-100 mb-10 tracking-tight">
    Crear nuevo usuario 👤
</h1>

<div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl 
            border border-gray-200 dark:border-gray-700 
            rounded-2xl shadow-2xl p-10 max-w-2xl mx-auto relative overflow-hidden">

    <!-- Glow decorativo -->
    <div class="absolute -top-10 -right-10 w-40 h-40 bg-orange-500/10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-purple-500/10 rounded-full blur-3xl"></div>

    {{-- Errores --}}
    @if ($errors->any())
        <div class="p-4 mb-8 bg-red-100 dark:bg-red-900 
                    text-red-700 dark:text-red-200 rounded-lg 
                    border border-red-300 dark:border-red-800 shadow">
            <ul class="ml-4 list-disc text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulario --}}
    <form method="POST" action="{{ route('superadmin.users.store') }}" class="space-y-8">
        @csrf

        {{-- Empresa --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                Empresa a la que pertenece
            </label>

            <div class="relative">
                <span class="absolute left-3 top-3 text-gray-500 dark:text-gray-300 text-lg">🏢</span>

                <select name="tenant_id" required
                    class="w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600
                           rounded-lg pl-10 pr-4 py-3 text-gray-900 dark:text-white
                           focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition">

                    <option value="" disabled selected>Seleccione una empresa...</option>

                    @foreach ($tenants as $tenant)
                        <option value="{{ $tenant->id }}">
                            {{ $tenant->name }} — {{ $tenant->slug }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Nombre --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                Nombre completo
            </label>

            <div class="relative">
                <span class="absolute left-3 top-3 text-purple-500 dark:text-purple-300">👤</span>

                <input type="text" name="name" required
                    class="w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600
                           rounded-lg pl-10 pr-4 py-3 text-gray-900 dark:text-white
                           focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                    placeholder="Juan Pérez">
            </div>
        </div>

        {{-- Email --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                Email
            </label>

            <div class="relative">
                <span class="absolute left-3 top-3 text-blue-500 dark:text-blue-300">✉️</span>

                <input type="email" name="email" required
                    class="w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600
                           rounded-lg pl-10 pr-4 py-3 text-gray-900 dark:text-white
                           focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                    placeholder="usuario@empresa.com">
            </div>
        </div>

        {{-- Password --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                Contraseña
            </label>

            <div class="relative">
                <span class="absolute left-3 top-3 text-orange-500 dark:text-orange-300">🔒</span>

                <input type="password" name="password" required
                    class="w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600
                           rounded-lg pl-10 pr-4 py-3 text-gray-900 dark:text-white
                           focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                    placeholder="••••••••">
            </div>
        </div>

        {{-- Botón --}}
        <div>
            <button type="submit"
                class="w-full py-3 bg-gradient-to-r from-orange-600 to-orange-700 
                       hover:from-orange-700 hover:to-orange-800
                       text-white font-bold rounded-xl shadow-lg
                       transform hover:scale-[1.03] active:scale-[0.97]
                       transition duration-150">
                ➕ Crear usuario
            </button>
        </div>

    </form>

</div>

@endsection
