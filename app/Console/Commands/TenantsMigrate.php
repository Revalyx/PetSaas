<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Models\Tenant;

class TenantsMigrate extends Command
{
    protected $signature = 'tenants:migrate {--fresh}';
    protected $description = 'Ejecuta migraciones en todas las bases de datos tenant';

    public function handle()
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {

            $this->info("------------------------------------------------------");
            $this->info("Migrando tenant: {$tenant->slug} ({$tenant->db_name})");
            $this->info("------------------------------------------------------");

            // ============================================================
            // CONFIGURACIÓN DINÁMICA REAL DE LA CONEXIÓN DEL TENANT
            // ============================================================
            config([
                'database.connections.tenant' => [
                    'driver'    => 'mysql',
                    'host'      => $tenant->db_host,
                    'database'  => $tenant->db_name,
                    'username'  => $tenant->db_username,
                    'password'  => decrypt($tenant->db_password),
                    'charset'   => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                ]
            ]);

            // PURGAR + RECONECTAR
            DB::purge('tenant');
            DB::reconnect('tenant');

            // ============================================================
            // EJECUTAR MIGRACIONES DEL TENANT
            // ============================================================
            $params = [
                '--database' => 'tenant',
                '--path'     => 'database/migrations/tenant',
                '--force'    => true,
            ];

            try {
                if ($this->option('fresh')) {
                    Artisan::call('migrate:fresh', $params);
                } else {
                    Artisan::call('migrate', $params);
                }

                $this->info(Artisan::output());

            } catch (\Exception $e) {
                $this->error("ERROR en tenant {$tenant->slug}: {$e->getMessage()}");
                continue;
            }
        }

        $this->info("Migraciones completadas en todos los tenants.");
    }
}
