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
    // Contador de clientes
    $clientes_count = Cliente::count();

    // Contador de mascotas (si existe la tabla)
    if (\Illuminate\Support\Facades\Schema::connection('tenant')->hasTable('mascotas')) {
        $mascotas_count = DB::connection('tenant')->table('mascotas')->count();
    } else {
        $mascotas_count = 0;
    }

    // Contador de citas (si existe la tabla)
    if (Schema::connection('tenant')->hasTable('citas')) {
        $citas_hoy = DB::connection('tenant')
            ->table('citas')
            ->whereDate('fecha_hora', now()->toDateString())
            ->count();
    } else {
        $citas_hoy = 0;
    }

    return view('tenant.dashboard', compact(
        'clientes_count',
        'mascotas_count',
        'citas_hoy'
    ));
}

}
