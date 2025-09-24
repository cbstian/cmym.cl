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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');

            // Información del producto al momento de la compra
            $table->string('product_name');
            $table->string('product_sku');
            $table->text('product_description')->nullable();
            $table->string('product_image_path')->nullable();

            // Cantidad y precios en pesos chilenos enteros
            $table->integer('quantity');
            $table->integer('unit_price'); // Precio unitario en pesos chilenos
            $table->integer('total_price'); // Precio total en pesos chilenos

            // Atributos del producto (color, talla, etc.)
            $table->json('product_attributes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
