<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;
use Exception;

class TenantMiddleware
{
    public function handle($request, Closure $next)
{
    $user = auth()->user();

    if (!$user || !$user->tenant_id) {
        abort(403, "No tienes tenant asignado.");
    }

    $tenant = Tenant::find($user->tenant_id);

    if (!$tenant) {
        abort(500, "Tenant no encontrado.");
    }

    // CONFIGURAR CONEXIÓN DINÁMICA
    config([
        'database.connections.tenant.host'     => $tenant->db_host,
        'database.connections.tenant.database' => $tenant->db_name,
        'database.connections.tenant.username' => $tenant->db_username,
        'database.connections.tenant.password' => decrypt($tenant->db_password),
    ]);

    // Aplicar
    \DB::purge('tenant');
    \DB::reconnect('tenant');

    // Pasar tenant al request
    $request->tenant = $tenant;

    return $next($request);
}


}
