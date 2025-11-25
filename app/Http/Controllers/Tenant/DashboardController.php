<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Mascota;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        Carbon::setLocale('es');

        // MÉTRICAS PRINCIPALES
        $clientes_count = Cliente::count();
        $mascotas_count = Mascota::count();
        $citas_hoy = Appointment::whereDate('date', Carbon::today())->count();

        // PRÓXIMAS CITAS
        $proximas_citas = Appointment::whereDate('date', '>=', Carbon::today())
            ->orderBy('date')
            ->orderBy('start_time')
            ->take(5)
            ->get();

        // CLIENTE MÁS ACTIVO
        $cliente_top = Cliente::select('clientes.*')
            ->selectRaw('(SELECT COUNT(*) FROM appointments WHERE appointments.customer_id = clientes.id) as total_citas')
            ->orderByDesc('total_citas')
            ->first();

        // MASCOTA MÁS ATENDIDA
        $mascota_top = Mascota::select('mascotas.*')
            ->selectRaw('(SELECT COUNT(*) FROM appointments WHERE appointments.pet_id = mascotas.id) as total_citas')
            ->orderByDesc('total_citas')
            ->first();

        // ESTADO HOY
        $estado_hoy = Appointment::whereDate('date', Carbon::today())
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // ----------------------------
        // 📊 GRÁFICO SEMANAL (EN ESPAÑOL)
        // ----------------------------


$labels_semana = [];
$datos_semana = [];

// Empezamos en lunes de esta semana
$inicioSemana = Carbon::now()->startOfWeek(Carbon::MONDAY);

// Generamos LUN → SAB
for ($i = 0; $i < 6; $i++) {
    $dia = $inicioSemana->copy()->addDays($i);

    // Etiquetas en español abreviado
    $labels_semana[] = $dia->translatedFormat('D d');

    // Número de citas ese día
    $datos_semana[] = Appointment::whereDate('date', $dia)->count();
}



        // ----------------------------
        // 📊 GRÁFICO MENSUAL
        // ----------------------------
        $labels_mes = [];
        $datos_mes = [];

        $days_in_month = Carbon::now()->daysInMonth;

        for ($d = 1; $d <= $days_in_month; $d++) {
            $date = Carbon::now()->copy()->day($d);
            $labels_mes[] = $date->format('d');
            $datos_mes[] = Appointment::whereDate('date', $date)->count();
        }

        // ----------------------------
        // 🔥 HEATMAP DE HORAS
        // ----------------------------
        $horas = [];
        for ($h = 8; $h <= 20; $h++) {
            $horas[$h] = Appointment::where('start_time', 'like', sprintf('%02d', $h) . ':%')->count();
        }

        return view('tenant.dashboard', compact(
            'clientes_count',
            'mascotas_count',
            'citas_hoy',
            'proximas_citas',
            'cliente_top',
            'mascota_top',
            'estado_hoy',
            'labels_semana',
            'datos_semana',
            'labels_mes',
            'datos_mes',
            'horas'
        ));
    }
}
