<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AlterTenantProducts extends Command
{
    protected $signature = 'tenant:alter-products';
    protected $description = 'Añade nuevas columnas a la tabla products en todos los tenants existentes';

    public function handle()
    {
        // Leer tenants desde petsaas_core
        $tenants = DB::table('tenants')->get();

        foreach ($tenants as $tenant) {

            $database = $tenant->db_name;

            $this->info("Actualizando products en tenant: {$tenant->name} ($database)");

            DB::statement("USE `$database`");

            // 1. Añadir image_path
            try {
                DB::statement("ALTER TABLE products ADD COLUMN image_path VARCHAR(255) NULL AFTER stock");
                $this->info("- Añadido image_path");
            } catch (\Exception $e) {
                $this->info("- image_path ya existe");
            }

            // 2. Añadir image_alt
            try {
                DB::statement("ALTER TABLE products ADD COLUMN image_alt VARCHAR(255) NULL AFTER image_path");
                $this->info("- Añadido image_alt");
            } catch (\Exception $e) {
                $this->info("- image_alt ya existe");
            }

            // 3. Añadir barcode
            try {
                DB::statement("ALTER TABLE products ADD COLUMN barcode VARCHAR(255) NULL AFTER image_alt");
                DB::statement("ALTER TABLE products ADD UNIQUE (barcode)");
                $this->info("- Añadido barcode");
            } catch (\Exception $e) {
                $this->info("- barcode ya existe o ya tiene índice");
            }

            $this->info("OK -> Tabla products actualizada en $database");
        }

        return Command::SUCCESS;
    }
}
