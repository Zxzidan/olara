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
        Schema::create('marketplace_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->json('items');
            $table->text('shipping_address');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('ppn_amount', 12, 2);
            $table->decimal('shipping_fee', 12, 2);
            $table->decimal('grand_total', 12, 2);
            $table->string('payment_method');
            $table->string('payment_status')->default('paid');
            $table->decimal('co2_saved_kg', 8, 2)->default(0);
            $table->integer('points_earned')->default(0);
            $table->string('shipping_status')->default('dikirim'); // diproses, dikirim, sampai, selesai
            $table->string('courier_name')->default('JNE Trucking (JTR)');
            $table->string('tracking_number')->nullable();
            $table->date('estimated_delivery_date')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('recipient_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketplace_orders');
    }
};
