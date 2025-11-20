@extends('layouts.superadmin')

@section('title', 'Salud del sistema')

@section('content')

<div class="space-y-10">

    <!-- TÍTULO -->
    <h2 class="text-3xl font-bold text-white mb-4 flex items-center gap-2">
        🩺 Salud del Multi-Tenant
    </h2>

    <!-- CONTENEDOR GLOBAL -->
    <div class="w-full bg-white/5 dark:bg-white/5 rounded-3xl p-6 md:p-10 shadow-2xl border border-white/10">

        <!-- 🔍 BUSCADOR -->
        <div class="mb-8">
            <label class="text-gray-300 text-sm mb-2 block font-semibold">Buscar empresa</label>

            <div class="relative">
                <input
                    id="tenantSearch"
                    type="text"
                    placeholder="Escribe el nombre o slug…"
                    class="w-full px-4 py-3 bg-gray-800/60 border border-gray-700 rounded-xl 
                           text-white placeholder-gray-400 focus:ring-2 focus:ring-orange-500 
                           outline-none transition"
                >
                <span class="absolute right-3 top-3 text-gray-400">🔍</span>
            </div>
        </div>

        <!-- GRID RESPONSIVO -->
        <div id="tenantGrid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-10">

            @foreach ($status as $diag)

            {{-- TARJETA DEL TENANT --}}
            <div class="tenant-card bg-gray-900/40 dark:bg-gray-900/60 border border-white/10 
                        rounded-3xl shadow-xl p-8 flex flex-col justify-between 
                        backdrop-blur-xl hover:scale-[1.01] transition-all"
                 data-name="{{ strtolower($diag['tenant']->name) }}"
                 data-slug="{{ strtolower($diag['tenant']->slug) }}">

                <!-- Nombre -->
                <h3 class="text-2xl font-bold text-white flex items-center gap-2 mb-4">
                    🏢 {{ $diag['tenant']->name }}
                </h3>

                <!-- Identificador -->
                <p class="text-gray-300 text-sm mb-6">
                    <span class="font-semibold text-gray-400">Identificador:</span>
                    {{ $diag['tenant']->slug }}
                </p>

                <!-- Tamaño -->
                <div class="mb-6">
                    <p class="text-gray-400 text-sm font-semibold flex items-center gap-2 mb-1">
                        🧱 Tamaño BD
                    </p>
                    <p class="text-lg font-bold text-orange-400">{{ $diag['size_mb'] }} MB</p>
                </div>

                <!-- Última migración -->
                <div class="mb-6">
                    <p class="text-gray-400 text-sm font-semibold flex items-center gap-2 mb-1">
                        📜 Última migración
                    </p>
                    <p class="text-gray-200">{{ $diag['last_migration'] }}</p>
                </div>

                <!-- Tablas -->
                <div class="mb-8">
                    <p class="text-gray-400 text-sm font-semibold flex items-center gap-2 mb-1">
                        🗂 Tablas detectadas
                    </p>
                    <ul class="list-disc ml-5 text-gray-300 text-sm space-y-1">
                        @foreach ($diag['tables'] as $tbl)
                            <li>{{ $tbl }}</li>
                        @endforeach
                    </ul>
                </div>

                <!-- Estado -->
                <div class="mt-auto">
                    @if ($diag['health'] === 'OK')
                        <span class="inline-block px-4 py-2 rounded-full bg-green-600 text-white font-semibold">
                            OK
                        </span>
                    @else
                        <span class="inline-block px-4 py-2 rounded-full bg-red-600 text-white font-semibold">
                            {{ $diag['health'] }}
                        </span>
                    @endif
                </div>

            </div>
            @endforeach

        </div>
    </div>

</div>

<!-- SCRIPT FILTRO -->
<script>
document.getElementById('tenantSearch').addEventListener('input', function() {
    const search = this.value.toLowerCase();
    const cards = document.querySelectorAll('.tenant-card');

    cards.forEach(card => {
        const name = card.dataset.name;
        const slug = card.dataset.slug;

        if (name.includes(search) || slug.includes(search)) {
            card.classList.remove('hidden');
        } else {
            card.classList.add('hidden');
        }
    });
});
</script>

@endsection
