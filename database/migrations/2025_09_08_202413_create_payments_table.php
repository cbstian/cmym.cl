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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');

            // Información básica del pago
            $table->enum('method', ['webpay', 'transfer'])->default('webpay');
            $table->string('transaction_id')->nullable();
            $table->string('session_id')->nullable();
            $table->integer('amount'); // Monto en pesos chilenos enteros
            $table->string('currency', 3)->default('CLP');

            // Estados del pago - usando los mismos que en Payment model
            $table->enum('status', ['pending', 'authorized', 'paid', 'failed', 'cancelled', 'refunded'])->default('pending');

            // Token de Transbank
            $table->string('token')->nullable();

            // Códigos de autorización y respuesta de Transbank
            $table->string('authorization_code')->nullable();
            $table->string('response_code')->nullable();

            // Datos de respuesta completos de Transbank (JSON)
            $table->json('response_data')->nullable();

            $table->timestamps();

            // Índices para optimizar búsquedas
            $table->index('token');
            $table->index('transaction_id');
            $table->index(['order_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
