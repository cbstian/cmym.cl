<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_id')->constrained('customers');
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->string('currency', 3)->default('CLP');

            // Montos en pesos chilenos enteros (integer)
            $table->integer('subtotal')->default(0);
            $table->integer('shipping_cost')->default(0); // Siempre 0 por ahora
            $table->integer('discount_amount')->default(0);
            $table->integer('total_amount')->default(0);

            // Estados y métodos de pago
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('payment_method')->nullable();

            // Direcciones
            $table->foreignId('billing_address_id')->constrained('addresses');
            $table->foreignId('shipping_address_id')->constrained('addresses');

            // Notas y fechas
            $table->text('notes')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
