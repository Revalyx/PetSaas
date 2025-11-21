<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ============================
        // CONTADOR CLIENTES
        // ============================
        $clientes_count = Cliente::count();

        // ============================
        // CONTADOR MASCOTAS
        // ============================
        $mascotas_count = Schema::connection('tenant')->hasTable('mascotas')
            ? DB::connection('tenant')->table('mascotas')->count()
            : 0;

        // ============================
        // CONTADOR CITAS HOY
        // ============================
        $citas_hoy = Schema::connection('tenant')->hasTable('citas')
            ? DB::connection('tenant')->table('citas')
                ->whereDate('fecha_hora', now()->toDateString())
                ->count()
            : 0;

        return view('tenant.dashboard', compact(
            'clientes_count',
            'mascotas_count',
            'citas_hoy'
        ));
    }
}
