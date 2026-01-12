<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();

            // Identificador único del producto (viene del CSV y/o del pedido)
            $table->string('sku', 80)->unique();

            // Datos descriptivos
            $table->string('name', 180)->nullable();
            $table->text('description')->nullable();

            // Cantidades
            $table->unsignedInteger('stock')->default(0);      // stock disponible
            $table->unsignedInteger('reserved')->default(0);   // stock reservado (opcional, para trazabilidad)
            $table->unsignedInteger('min_stock')->default(0);  // umbral opcional

            // Precio opcional (puede venir del CSV)
            $table->decimal('price', 12, 2)->nullable();
            $table->string('currency', 10)->default('USD');

            // Para integración por archivos / trazabilidad
            $table->string('source_file', 255)->nullable();        // nombre del CSV procesado
            $table->timestamp('source_imported_at')->nullable();   // cuándo se importó ese registro

            $table->timestamps();

            // Índices útiles para búsquedas y listados
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
