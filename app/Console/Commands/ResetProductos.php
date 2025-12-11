<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetProductos extends Command
{
    protected $signature = 'productos:reset';
    protected $description = 'Vacía y actualiza la tabla productos en todas las bases de datos tenant';

    public function handle()
    {
        $this->info('Leyendo tenants desde petsaas_core...');

        // Leer tenants de la base principal
        $tenants = DB::connection('mysql')->table('tenants')->get();

        foreach ($tenants as $tenant) {

            $this->info("Procesando tenant ID {$tenant->id} ({$tenant->db_name})...");

            // Crear conexión dinámica usando ROOT SIEMPRE
            config(['database.connections.tenant' => [
    'driver'   => 'mysql',
    'host'     => $tenant->db_host,
    'port'     => env('DB_PORT', 3306),
    'database' => $tenant->db_name,
    'username' => 'petsaas_app',
    'password' => 'E.reyesVaq1993$',
    'charset'  => 'utf8mb4',
    'collation'=> 'utf8mb4_unicode_ci',
]]);


            // Forzar reconexión
            DB::purge('tenant');
            DB::reconnect('tenant');

            // Ejecutar TRUNCATE
            try {
                DB::connection('tenant')->statement("TRUNCATE TABLE productos;");
                $this->info(" - Tabla productos vaciada.");
            } catch (\Exception $e) {
                $this->error(" - ERROR al vaciar productos en {$tenant->db_name}: {$e->getMessage()}");
                continue;
            }
        }

        $this->info("Proceso completado.");
    }
}
