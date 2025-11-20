<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;

class TenantMiddleware
{
    public function handle($request, Closure $next)
    {
        // ========================================================
        // 1) Ignorar rutas que NO deben aplicar tenant
        // ========================================================
        if (
            $request->is('login') ||
            $request->is('logout') ||
            $request->is('superadmin/*') ||
            $request->is('superadmin') ||
            $request->routeIs('superadmin.*')
        ) {
            return $next($request);
        }

        // ========================================================
        // 2) Usuario no autenticado → seguir normal (irá a login)
        // ========================================================
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // ========================================================
        // 3) Superadmin → NO se fuerza tenant
        // ========================================================
        if ($user->tenant_id === null) {
            return $next($request);
        }

        // ========================================================
        // 4) Cargar información del tenant
        // ========================================================
        $tenant = Tenant::find($user->tenant_id);

        if (!$tenant) {
            abort(500, "El tenant asociado al usuario no existe.");
        }

        // ========================================================
        // 5) Configurar conexión dinámica para el tenant
        // ========================================================
        config([
            'database.connections.tenant.host'     => $tenant->db_host,
            'database.connections.tenant.database' => $tenant->db_name,
            'database.connections.tenant.username' => $tenant->db_username,
            'database.connections.tenant.password' => decrypt($tenant->db_password),
        ]);

        DB::purge('tenant'); // Limpia cualquier conexión previa
        DB::setDefaultConnection('tenant'); // La conexión por defecto será tenant

        // Guardamos el tenant actual en el request para debug
        $request->tenant = $tenant;

        // ========================================================
        // 6) Ejecutamos la request ya bajo tenant
        // ========================================================
        $response = $next($request);

        // ========================================================
        // 7) Restaurar conexión a MySQL por seguridad
        // ========================================================
        DB::setDefaultConnection(config('database.default'));

        return $response;
    }
}
