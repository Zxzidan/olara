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
        Schema::table('marketplace_orders', function (Blueprint $table) {
            $table->string('snap_token')->nullable()->after('payment_method');
            $table->string('payment_type')->nullable()->after('snap_token');
            $table->string('transaction_id')->nullable()->after('payment_type');
            $table->json('payment_response')->nullable()->after('transaction_id');
        });

        Schema::table('pickup_requests', function (Blueprint $table) {
            $table->string('payment_status')->default('unpaid')->after('total_fee');
            $table->string('payment_method')->nullable()->after('payment_status');
            $table->string('snap_token')->nullable()->after('payment_method');
            $table->string('transaction_id')->nullable()->after('snap_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketplace_orders', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'payment_type', 'transaction_id', 'payment_response']);
        });

        Schema::table('pickup_requests', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'payment_method', 'snap_token', 'transaction_id']);
        });
    }
};
