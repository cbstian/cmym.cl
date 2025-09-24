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
        Schema::table('order_items', function (Blueprint $table) {
            $table->json('product_attributes')->nullable()->after('total_price');
            $table->text('product_description')->nullable()->after('product_attributes');
            $table->string('product_image_path')->nullable()->after('product_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['product_attributes', 'product_description', 'product_image_path']);
        });
    }
};
