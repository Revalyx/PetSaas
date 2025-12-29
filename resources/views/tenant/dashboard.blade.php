@extends('layouts.tenant')

@section('title', 'Dashboard')

@section('content')

<div class="space-y-10">

    <!-- KPIS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        <div class="p-6 rounded-2xl bg-gradient-to-br from-teal-600 to-teal-700 text-white shadow-lg">
            <p class="text-sm opacity-80">Clientes Totales Registrados</p>
            <p class="text-5xl font-bold mt-1">{{ $clientes_count }}</p>
        </div>

        <div class="p-6 rounded-2xl bg-gradient-to-br from-slate-600 to-slate-700 text-white shadow-lg">
            <p class="text-sm opacity-80">Mascotas Totales Registradas</p>
            <p class="text-5xl font-bold mt-1">{{ $mascotas_count }}</p>
        </div>

        <div class="p-6 rounded-2xl bg-gradient-to-br from-teal-500 to-teal-600 text-white shadow-lg">
            <p class="text-sm opacity-80">Citas hoy</p>
            <p class="text-5xl font-bold mt-1">{{ $citas_hoy }}</p>
        </div>

    </div>

    <!-- GRÁFICAS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- Gráfico semanal -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700">
            <h2 class="text-xl font-bold mb-4">Citas esta semana</h2>

            <!-- ALTURA CONTROLADA AQUÍ -->
            <div class="h-[260px]">
                <canvas id="graficoSemana"></canvas>
            </div>
        </div>

        <!-- Gráfico mensual -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700">
            <h2 class="text-xl font-bold mb-4">Citas este mes</h2>

            <!-- ALTURA CONTROLADA AQUÍ -->
            <div class="h-[260px]">
                <canvas id="graficoMes"></canvas>
            </div>
        </div>

    </div>

    <!-- HEATMAP HORAS -->
    <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700">

        <div class="flex items-center justify-between mb-5">
            <h2 class="text-xl font-bold">Horas más ocupadas (HOY)</h2>
            <span class="text-sm text-slate-500 dark:text-slate-400">
                Intensidad por número de citas
            </span>
        </div>

        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-4">
            @foreach($horas as $hora => $count)
                @php
                    if ($count === 0) {
                        $bg = 'bg-teal-50 dark:bg-teal-900/20 text-slate-700 dark:text-slate-300';
                    } elseif ($count <= 2) {
                        $bg = 'bg-teal-200 dark:bg-teal-700/40 text-slate-900 dark:text-white';
                    } elseif ($count <= 4) {
                        $bg = 'bg-teal-400 dark:bg-teal-600 text-white';
                    } else {
                        $bg = 'bg-teal-600 dark:bg-teal-500 text-white';
                    }
                @endphp

                <div class="p-4 rounded-xl text-center transition hover:scale-[1.04] hover:shadow-md {{ $bg }}">
                    <p class="text-base font-bold">{{ $hora }}:00</p>
                    <p class="text-xs opacity-80 mt-1">{{ $count }} citas</p>
                </div>
            @endforeach
        </div>
    </div>
    <!-- SEGUNDA FILA: TARJETAS RESUMEN -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- Próximas citas -->
    <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700">
        <h2 class="text-xl font-bold mb-4">
            Próximas citas
        </h2>

        @forelse($proximas_citas as $cita)
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700 mb-3">
                <p class="font-semibold">
                    {{ $cita->customer->nombre ?? '—' }}
                    {{ $cita->customer->apellidos ?? '' }}
                </p>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    {{ \Carbon\Carbon::parse($cita->date)->format('d/m/Y') }}
                    —
                    {{ $cita->start_time
                        ? \Carbon\Carbon::parse($cita->start_time)->format('H:i')
                        : '--:--' }}
                </p>
            </div>
        @empty
            <p class="text-slate-500 dark:text-slate-400">
                No hay próximas citas.
            </p>
        @endforelse
    </div>

    <!-- Cliente más activo -->
    <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700">
        <h2 class="text-xl font-bold mb-4">
            Cliente más activo
        </h2>

        @if($cliente_top && $cliente_top->total_citas > 0)
            <p class="text-lg font-semibold">
                {{ $cliente_top->nombre }} {{ $cliente_top->apellidos }}
            </p>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">
                Citas totales:
                <span class="font-bold text-teal-600 dark:text-teal-400">
                    {{ $cliente_top->total_citas }}
                </span>
            </p>
        @else
            <p class="text-slate-500 dark:text-slate-400">
                Todavía no hay datos.
            </p>
        @endif
    </div>

    <!-- Mascota más atendida -->
    <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700">
        <h2 class="text-xl font-bold mb-4">
            Mascota más atendida
        </h2>

        @if($mascota_top && $mascota_top->total_citas > 0)
            <p class="text-lg font-semibold">
                {{ $mascota_top->nombre }}
            </p>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">
                Citas totales:
                <span class="font-bold text-teal-600 dark:text-teal-400">
                    {{ $mascota_top->total_citas }}
                </span>
            </p>
        @else
            <p class="text-slate-500 dark:text-slate-400">
                Todavía no hay datos.
            </p>
        @endif
    </div>

</div>


</div>

<!-- SCRIPTS DE GRÁFICOS -->
<script>
document.addEventListener("DOMContentLoaded", () => {

    if (typeof Chart === "undefined") return;

    const isDark = document.documentElement.classList.contains("dark");
    const textColor = isDark ? "#e5e7eb" : "#334155";
    const gridColor = isDark ? "rgba(255,255,255,0.08)" : "rgba(0,0,0,0.08)";
    const teal = "#14b8a6";

    new Chart(document.getElementById("graficoSemana"), {
        type: "line",
        data: {
            labels: @json($labels_semana),
            datasets: [{
                label: "Citas",
                data: @json($datos_semana),
                borderColor: teal,
                backgroundColor: "rgba(20,184,166,0.25)",
                tension: 0.3,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: teal
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { labels: { color: textColor } } },
            scales: {
                x: { ticks: { color: textColor }, grid: { color: gridColor } },
                y: { ticks: { color: textColor }, grid: { color: gridColor } }
            }
        }
    });

    new Chart(document.getElementById("graficoMes"), {
        type: "bar",
        data: {
            labels: @json($labels_mes),
            datasets: [{
                label: "Citas por día",
                data: @json($datos_mes),
                backgroundColor: teal,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { labels: { color: textColor } } },
            scales: {
                x: { ticks: { color: textColor }, grid: { color: gridColor } },
                y: { ticks: { color: textColor }, grid: { color: gridColor } }
            }
        }
    });

});
</script>

@endsection
