<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->uuid('correlation_id')->index();

            $table->string('customer_email')->nullable();

            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('currency', 3)->default('USD');

            $table->string('status', 32)->default('PENDING')->index();

            // Items del pedido (ej: [{"sku":"SKU-1","qty":1,"price":10.5}])
            $table->json('items');

            // Payload completo recibido (opcional para auditoría/debug)
            $table->json('payload')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
