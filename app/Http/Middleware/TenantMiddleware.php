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
        // 2) Usuario no autenticado → no se puede cargar tenant
        // ========================================================
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // ========================================================
        // 3) Si el usuario NO tiene tenant_id → es superadmin
        // ========================================================
        if ($user->tenant_id === null) {
            return $next($request);
        }

        // ========================================================
        // 4) Buscar BD del tenant
        // ========================================================
        $tenant = Tenant::find($user->tenant_id);

        if (!$tenant) {
            abort(500, "El tenant asociado al usuario no existe.");
        }

        // ========================================================
        // 5) Configurar conexión dinámica tenant
        // ========================================================
        config([
            'database.connections.tenant.host'     => $tenant->db_host,
            'database.connections.tenant.database' => $tenant->db_name,
            'database.connections.tenant.username' => $tenant->db_username,
            'database.connections.tenant.password' => decrypt($tenant->db_password),
        ]);

        DB::purge('tenant');               // Limpia caché de conexiones
        DB::setDefaultConnection('tenant'); // Marca tenant como conexión por defecto

        // Para debugging opcional
        $request->tenant = $tenant;

        // ========================================================
        // 6) Procesamos la request bajo la BD tenant
        // ========================================================
        $response = $next($request);

        // ========================================================
        // 7) Restauramos conexión principal por seguridad
        // ========================================================
        DB::setDefaultConnection(config('database.default'));

        return $response;
    }
}
