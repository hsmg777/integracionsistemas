<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics_daily', function (Blueprint $table) {
            $table->id();

            // Fecha del agregado (día)
            $table->date('date')->unique();

            // Pedidos
            $table->unsignedInteger('orders_total')->default(0);
            $table->unsignedInteger('orders_confirmed')->default(0);
            $table->unsignedInteger('orders_rejected')->default(0);

            // Dinero
            $table->decimal('revenue_total', 12, 2)->default(0);

            // Inventario
            $table->unsignedInteger('items_sold')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_daily');
    }
};
