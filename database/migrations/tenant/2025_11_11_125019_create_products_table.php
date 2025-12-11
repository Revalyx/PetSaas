<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('id_adicional')->nullable();
            $table->string('codigo_barras')->nullable();
            $table->string('categoria')->nullable();
            $table->string('producto');

            $table->decimal('precio', 10, 2)->default(0);
            $table->decimal('porcentaje_impuesto', 5, 2)->default(0);
            $table->decimal('pvp', 10, 2)->default(0);
            $table->decimal('precio_real', 10, 2)->default(0);
            $table->decimal('beneficio', 10, 2)->default(0);
            $table->decimal('margen', 10, 2)->default(0);
            $table->integer('stock')->default(0);

            $table->string('image_path')->nullable();
            $table->string('image_alt')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
