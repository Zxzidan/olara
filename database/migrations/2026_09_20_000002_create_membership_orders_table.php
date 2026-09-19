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
        Schema::create('membership_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('tier')->default('premium');
            $table->string('plan_type')->default('monthly'); // 'monthly' or 'yearly'
            $table->decimal('amount', 12, 2);
            $table->string('payment_status')->default('pending'); // pending, paid, failed, expired
            $table->string('payment_method')->nullable();
            $table->string('payment_type')->nullable(); // bank_transfer, qris, gopay, etc.
            $table->string('snap_token')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_orders');
    }
};
