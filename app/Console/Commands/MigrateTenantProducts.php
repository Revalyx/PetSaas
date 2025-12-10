<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateTenantProducts extends Command
{
    protected $signature = 'tenant:migrate-products';
    protected $description = 'Añade la tabla products a todas las bases de datos tenant existentes';

    public function handle()
    {
        // 1. Obtenemos todos los tenants desde petsaas_core.tenants
        $tenants = DB::table('tenants')->get();

        foreach ($tenants as $tenant) {

            // 2. Nombre real de la base de datos del tenant
            $database = $tenant->db_name;

            $this->info("Migrando tenant: {$tenant->name} ({$database}) ...");

            // 3. Usamos la base de datos del tenant
            DB::statement("USE `$database`");

            // 4. Creamos la tabla si NO existe
            DB::statement("
                CREATE TABLE IF NOT EXISTS `products` (
                  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                  `name` varchar(255) NOT NULL,
                  `description` text NULL,
                  `price` decimal(10,2) NOT NULL,
                  `stock` int NOT NULL DEFAULT 0,
                  `created_at` timestamp NULL,
                  `updated_at` timestamp NULL,
                  PRIMARY KEY (`id`)
                );
            ");

            $this->info("OK -> Tabla 'products' creada en $database");
        }

        return Command::SUCCESS;
    }
}
