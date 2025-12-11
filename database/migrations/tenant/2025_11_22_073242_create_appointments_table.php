<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            // Relación obligatoria con clientes
            $table->unsignedBigInteger('customer_id');

            // Opcionales
            $table->unsignedBigInteger('pet_id')->nullable();
            $table->unsignedBigInteger('service_id')->nullable(); // futuro

            // Datos de la cita
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->text('notes')->nullable();

            // Estado de la cita
            $table->enum('status', [
                'pending',
                'confirmed',
                'cancelled',
                'completed'
            ])->default('pending');

            $table->timestamps();

            // Foreign keys
            $table->foreign('customer_id')
                  ->references('id')->on('clientes')
                  ->onDelete('cascade');

            $table->foreign('pet_id')
                  ->references('id')->on('mascotas')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
