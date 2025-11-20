@extends('layouts.superadmin')

@section('title', 'Crear Empresa')

@section('content')

<div class="space-y-10">

    <!-- ========================================================
         CABECERA PREMIUM
    ========================================================== -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-extrabold text-white tracking-tight">
                Crear nueva empresa
            </h1>
            <p class="text-gray-400 mt-2 text-sm">
                Registre una nueva compañía dentro del ecosistema PetSaaS.
            </p>
        </div>

        <a href="{{ route('superadmin.dashboard') }}"
           class="px-5 py-2 rounded-xl bg-white/10 hover:bg-white/20 
                  text-gray-200 text-sm shadow transition backdrop-blur-lg">
            ← Volver al dashboard
        </a>
    </div>

    <!-- ========================================================
         TARJETA DE FORMULARIO — ULTRA PREMIUM
    ========================================================== -->
    <div class="bg-white/10 dark:bg-white/5 backdrop-blur-2xl 
                rounded-3xl border border-white/10 shadow-2xl p-10 
                max-w-3xl mx-auto animate-fadeIn">

        {{-- Errors --}}
        @if ($errors->any())
            <div class="mb-8 p-5 rounded-2xl border border-red-500/30 
                        bg-red-500/10 text-red-300">
                <ul class="list-disc ml-6 space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>⚠️ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Success --}}
        @if (session('success'))
            <div class="mb-8 p-5 rounded-2xl border border-green-500/30 
                        bg-green-500/10 text-green-300 text-sm">
                ✅ {{ session('success') }}
            </div>
        @endif


        <!-- ========================================================
             FORMULARIO
        ========================================================== -->
        <form method="POST" action="{{ route('superadmin.tenants.store') }}" class="space-y-8">
            @csrf


            <!-- Nombre Empresa -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-200">
                    Nombre de la empresa
                </label>

                <div class="relative group">
                    <span class="absolute left-4 top-3 text-gray-400 text-lg group-focus-within:text-orange-400 transition">
                        🏢
                    </span>

                    <input type="text" name="empresa" id="empresaInput"
                           class="w-full pl-12 pr-4 py-3 rounded-2xl bg-white/10 border border-white/20 
                                  focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                  placeholder-gray-400 text-gray-100 transition"
                           placeholder="Mis Mascotas S.L." required autofocus>
                </div>

                <p class="text-xs text-gray-400 ml-1">El nombre se usará para identificar visualmente la empresa.</p>
            </div>


            <!-- Slug -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-200">
                    Slug / Identificador único
                </label>

                <div class="relative group">
                    <span class="absolute left-4 top-3 text-gray-400 text-lg group-focus-within:text-orange-400 transition">
                        🔗
                    </span>

                    <input type="text" name="slug" id="slugInput"
                           class="w-full pl-12 pr-4 py-3 rounded-2xl bg-white/10 border border-white/20 
                                  focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                  placeholder-gray-400 text-gray-100 transition"
                           placeholder="mis-mascotas" required>
                </div>

                <p class="text-xs text-gray-400 ml-1">Debe ser único. Puede generarse automáticamente a partir del nombre.</p>

                <!-- Botón autogenerar slug -->
                <button type="button"
                        onclick="autoSlug()"
                        class="mt-2 px-4 py-1.5 bg-white/10 hover:bg-white/20 
                               text-xs text-gray-200 rounded-lg transition backdrop-blur 
                               border border-white/20">
                    Autogenerar a partir del nombre
                </button>
            </div>


            <!-- Botón CREAR -->
            <div class="pt-4">
                <button type="submit"
                        class="w-full py-4 rounded-2xl font-bold text-white text-lg
                               bg-gradient-to-r from-orange-600 to-pink-600
                               shadow-xl hover:shadow-2xl
                               transform hover:-translate-y-0.5 hover:scale-[1.01]
                               transition-all">
                    Crear empresa 🚀
                </button>
            </div>

        </form>

    </div>

</div>



<!-- ========================================================
     SCRIPT: GENERACIÓN AUTOMÁTICA DEL SLUG
============================================================ -->
<script>
    function autoSlug() {
        const empresa = document.getElementById('empresaInput').value;

        if (!empresa.trim()) return;

        const slug = empresa
            .toLowerCase()
            .normalize("NFD").replace(/[\u0300-\u036f]/g, "") // quitar tildes
            .replace(/[^a-z0-9]+/g, "-")  // espacios a guiones
            .replace(/^-+|-+$/g, "");    // quitar guiones extremos

        document.getElementById('slugInput').value = slug;
    }
</script>

@endsection
