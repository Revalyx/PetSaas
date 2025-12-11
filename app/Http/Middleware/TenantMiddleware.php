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

    \Log::info("TENANT MIDDLEWARE ACTIVO", [
        'tenant' => $tenant->slug,
        'db' => $tenant->db_name
    ]);

    config([
        'database.connections.tenant.host'     => $tenant->db_host,
        'database.connections.tenant.database' => $tenant->db_name,
        'database.connections.tenant.username' => $tenant->db_username,
        'database.connections.tenant.password' => decrypt($tenant->db_password),
    ]);

    DB::purge('tenant');
    DB::reconnect('tenant');

    return $next($request);
}



}
