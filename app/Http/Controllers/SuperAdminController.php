<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Tenant;
use App\Models\User;

class SuperAdminController extends Controller
{
    /**
     * ====================================================
     *  DASHBOARD SUPERADMIN
     * ====================================================
     */
    public function index()
    {
        return view('superadmin.dashboard', [
            'tenants'        => Tenant::all(),
            'users'          => User::count(),
            'total_tenants'  => Tenant::count(),
        ]);
    }


    /**
     * ====================================================
     *  LISTADO DE TENANTS
     * ====================================================
     */
    public function tenants()
    {
        return view('superadmin.tenants.index', [
            'tenants' => Tenant::all(),
        ]);
    }


    /**
     * ====================================================
     *  CREAR TENANT (FORMULARIO)
     * ====================================================
     */
    public function createTenant()
    {
        return view('superadmin.tenants.create');
    }


    /**
     * ====================================================
     *  GUARDAR NUEVO TENANT
     * ====================================================
     */
    public function storeTenant(Request $request)
{
    $request->validate([
        'empresa' => 'required|string|max:255',
        'slug'    => 'required|string|max:255|unique:tenants,slug',
    ]);

    $slug   = $request->slug;
    $dbName = $slug;

    // 1️⃣ Crear base de datos del tenant
    DB::statement("CREATE DATABASE `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    // 2️⃣ Registrar tenant
    $tenant = Tenant::create([
        'name'        => $request->empresa,
        'slug'        => $slug,
        'db_name'     => $dbName,
        'db_host'     => env('DB_HOST'),
        'db_username' => env('DB_USERNAME'),
        'db_password' => encrypt(env('DB_PASSWORD')),
    ]);

    // 3️⃣ Conectar al tenant
    config([
        'database.connections.tenant.host'     => $tenant->db_host,
        'database.connections.tenant.database' => $tenant->db_name,
        'database.connections.tenant.username' => $tenant->db_username,
        'database.connections.tenant.password' => decrypt($tenant->db_password),
    ]);

    DB::purge('tenant');

    // 4️⃣ Migraciones del tenant (YA SIN users)
    \Artisan::call('migrate', [
    '--database' => 'tenant',
    '--path'     => 'database/migrations/tenant',
    '--force'    => true,
]);

    return redirect()->route('superadmin.dashboard')
        ->with('success', 'Empresa creada con éxito.');
}




    /**
     * ====================================================
     *  ELIMINAR TENANT (BD + USUARIOS + REGISTRO)
     * ====================================================
     */
    public function destroy($id)
    {
        $tenant = Tenant::findOrFail($id);
        $dbName = $tenant->db_name;

        try {
            // 1️⃣ Borrar base de datos física
            DB::statement("DROP DATABASE IF EXISTS `$dbName`");

            // 2️⃣ Borrar usuarios asociados
            User::where('tenant_id', $tenant->id)->delete();

            // 3️⃣ Borrar registro del tenant
            $tenant->delete();

            return redirect()->route('superadmin.dashboard')
                ->with('success', 'La empresa y su base de datos han sido eliminadas correctamente.');

        } catch (\Exception $e) {

            return redirect()->route('superadmin.dashboard')
                ->with('error', 'Error al eliminar empresa: ' . $e->getMessage());
        }
    }


    /**
     * ====================================================
     *  CREAR USUARIO (FORMULARIO)
     * ====================================================
     */
    public function createUser()
    {
        return view('superadmin.users.create', [
            'tenants' => Tenant::all(),
        ]);
    }


    /**
     * ====================================================
     *  GUARDAR NUEVO USUARIO DE TENANT
     * ====================================================
     */
public function storeUser(Request $request)
{
    $request->validate([
        'tenant_id' => 'required|exists:tenants,id',
        'name'      => 'required|string|max:255',
        'email'     => 'required|email|unique:users,email',
        'password'  => 'required|min:6',
    ]);

    User::create([
        'tenant_id'   => $request->tenant_id,
        'name'        => $request->name,
        'email'       => $request->email,
        'password'    => Hash::make($request->password),
    ]);

    return redirect()->route('superadmin.dashboard')
        ->with('success', 'Usuario creado correctamente.');
}




    /**
     * ============================================================
     * 🟢 PANEL REAL DE “SALUD DEL MULTI-TENANT”
     * ============================================================
     */
    public function systemHealth()
    {
        $tenants = Tenant::all();
        $data = [];

        foreach ($tenants as $tenant) {

            // Configurar conexión
            config([
                'database.connections.tenant.host'     => $tenant->db_host,
                'database.connections.tenant.database' => $tenant->db_name,
                'database.connections.tenant.username' => $tenant->db_username,
                'database.connections.tenant.password' => decrypt($tenant->db_password),
            ]);

            DB::purge('tenant');

            // 1️⃣ Tamaño de BD
            $size = DB::select("
                SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                FROM information_schema.TABLES
                WHERE table_schema = ?
            ", [$tenant->db_name])[0]->size_mb ?? 0;

            // 2️⃣ Tablas
            $tables = DB::select("
                SELECT table_name 
                FROM information_schema.tables
                WHERE table_schema = ?
            ", [$tenant->db_name]);

            $tableNames = collect($tables)->pluck('table_name')->toArray();

            // 3️⃣ Última migración
            $lastMigration = "No hay tabla migrations";

            if (in_array('migrations', $tableNames)) {
                $mig = DB::select("
                    SELECT migration 
                    FROM migrations 
                    ORDER BY id DESC 
                    LIMIT 1
                ");

                $lastMigration = $mig ? $mig[0]->migration : "Sin migraciones registradas";
            }

            // 4️⃣ Estado
            $status = "OK";
            

            // Añadir al array
            $data[] = [
                'tenant'         => $tenant,
                'size_mb'        => $size,
                'tables'         => $tableNames,
                'last_migration' => $lastMigration,
                'health'         => $status,
            ];
        }

        return view('superadmin.health.index', [
            'status' => $data
        ]);
    }
}
