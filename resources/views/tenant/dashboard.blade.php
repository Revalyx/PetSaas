@extends('layouts.tenant')

@section('title', 'Dashboard')

@section('content')

<div class="space-y-10">

    <!-- KPIS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        <div class="p-6 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-lg">
            <p class="text-sm opacity-80">Clientes Registrados</p>
            <p class="text-5xl font-bold mt-1">{{ $clientes_count }}</p>
        </div>

        <div class="p-6 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 text-white shadow-lg">
            <p class="text-sm opacity-80">Mascotas Registradas</p>
            <p class="text-5xl font-bold mt-1">{{ $mascotas_count }}</p>
        </div>

        <div class="p-6 rounded-2xl bg-gradient-to-br from-teal-500 to-teal-600 text-white shadow-lg">
            <p class="text-sm opacity-80">Citas hoy</p>
            <p class="text-5xl font-bold mt-1">{{ $citas_hoy }}</p>
        </div>

    </div>

    <!-- PRIMERA FILA DE DASHBOARD: GRAFICOS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- Gráfico semanal -->
        <div class="bg-white/10 dark:bg-white/5 p-6 rounded-3xl shadow-xl border border-white/10">
            <h2 class="text-xl font-bold mb-4">Citas esta semana</h2>
            <canvas id="graficoSemana" class="h-72 w-full"></canvas>
        </div>

        <!-- Gráfico mensual -->
        <div class="bg-white/10 dark:bg-white/5 p-6 rounded-3xl shadow-xl border border-white/10">
            <h2 class="text-xl font-bold mb-4">Citas este mes</h2>
            <canvas id="graficoMes" class="h-72 w-full"></canvas>
        </div>

    </div>

    <!-- HEATMAP HORAS -->
    <div class="bg-white/10 dark:bg-white/5 p-6 rounded-3xl shadow-xl border border-white/10">
        <h2 class="text-xl font-bold mb-4">Horas más ocupadas</h2>

        <div class="grid grid-cols-7 gap-2">
            @foreach($horas as $hora => $count)
                <div class="p-4 rounded-xl text-center"
                     style="background-color: rgba(0, 150, 255, {{ min($count / 5, 1) }});">
                    <p class="font-bold">{{ $hora }}:00</p>
                    <p class="text-sm">{{ $count }} citas</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- SEGUNDA FILA: TARJETAS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Próximas citas -->
        <div class="bg-white/10 dark:bg-white/5 p-6 rounded-3xl shadow-xl border border-white/10">
            <h2 class="text-xl font-bold mb-4">Próximas citas</h2>

            @forelse($proximas_citas as $cita)
                <div class="p-4 rounded-xl bg-white/5 mb-3">
                    <p class="font-semibold">
                        {{ $cita->customer->nombre ?? '—' }} {{ $cita->customer->apellidos ?? '' }}
                    </p>
                    <p class="text-sm text-gray-400">
                        {{ \Carbon\Carbon::parse($cita->date)->format('d/m/Y') }} –
                        {{ $cita->start_time ? \Carbon\Carbon::parse($cita->start_time)->format('H:i') : '--:--' }}
                    </p>
                </div>
            @empty
                <p class="text-gray-400">No hay próximas citas.</p>
            @endforelse
        </div>

        <!-- Cliente más activo -->
        <div class="bg-white/10 dark:bg-white/5 p-6 rounded-3xl shadow-xl border border-white/10">
            <h2 class="text-xl font-bold mb-4">Cliente más activo</h2>

            @if($cliente_top && $cliente_top->total_citas > 0)
                <p class="text-lg font-semibold">
                    {{ $cliente_top->nombre }} {{ $cliente_top->apellidos }}
                </p>
                <p class="text-gray-400 text-sm">
                    Citas totales: <b>{{ $cliente_top->total_citas }}</b>
                </p>
            @else
                <p class="text-gray-400">Todavía no hay datos.</p>
            @endif
        </div>

        <!-- Mascota más atendida -->
        <div class="bg-white/10 dark:bg-white/5 p-6 rounded-3xl shadow-xl border border-white/10">
            <h2 class="text-xl font-bold mb-4">Mascota más atendida</h2>

            @if($mascota_top && $mascota_top->total_citas > 0)
                <p class="text-lg font-semibold">{{ $mascota_top->nombre }}</p>
                <p class="text-gray-400 text-sm">
                    Citas totales: <b>{{ $mascota_top->total_citas }}</b>
                </p>
            @else
                <p class="text-gray-400">Todavía no hay datos.</p>
            @endif
        </div>

    </div>

</div>



<!-- SCRIPTS DE GRÁFICOS -->
<script>
document.addEventListener("DOMContentLoaded", () => {

    if (typeof Chart === "undefined") {
        console.error("❌ Chart.js no está cargado");
        return;
    }

    // ===============================
    //      GRÁFICO SEMANAL
    // ===============================

    const labelsSemana = @json($labels_semana);
    const datosSemana = @json($datos_semana);

    new Chart(document.getElementById('graficoSemana'), {
        type: 'line',
        data: {
            labels: labelsSemana, // ← AQUI VAN TUS FECHAS
            datasets: [{
                label: 'Citas',
                data: datosSemana,
                borderColor: '#38bdf8',
                backgroundColor: 'rgba(56, 189, 248, .3)',
                tension: .3,
                fill: true
            }]
        },
        options: {
            scales: {
                x: {
                    type: 'category',    // ← OBLIGATORIO
                    ticks: {
                        color: "#ffffff",
                        callback: function(value, index) {
                            return labelsSemana[index];
                        }
                    }
                },
                y: {
                    ticks: { color: "#ffffff" }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        title: function(ctx) {
                            return labelsSemana[ctx[0].dataIndex]; // ← FECHA REAL EN ESPAÑOL
                        },
                        label: function(ctx) {
                            return "Citas: " + ctx.raw;
                        }
                    }
                },
                legend: { labels: { color: "#ffffff" } }
            }
        }
    });



    // ===============================
    //      GRÁFICO MENSUAL
    // ===============================

    const labelsMes = @json($labels_mes);
    const datosMes = @json($datos_mes);

    new Chart(document.getElementById('graficoMes'), {
        type: 'bar',
        data: {
            labels: labelsMes,
            datasets: [{
                label: 'Citas por día',
                data: datosMes,
                backgroundColor: '#8b5cf6'
            }]
        },
        options: {
            scales: {
                x: {
                    type: "category",
                    ticks: { color: "#ffffff" }
                },
                y: {
                    ticks: { color: "#ffffff" }
                }
            },
            plugins: {
                legend: { labels: { color: "#ffffff" } }
            }
        }
    });

});
</script>



@endsection
