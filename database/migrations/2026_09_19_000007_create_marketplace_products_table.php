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
        Schema::create('marketplace_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('grade');
            $table->string('category');
            $table->decimal('price_per_kg', 10, 2);
            $table->integer('min_order_kg');
            $table->integer('stock_kg');
            $table->text('description');
            $table->string('image_url')->nullable();
            $table->decimal('co2_savings_per_kg', 6, 2)->default(1.8);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketplace_products');
    }
};
