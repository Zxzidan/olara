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
        // 1. Eco-Point Transactions
        Schema::create('eco_point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('amount'); // e.g. +50, -200
            $table->string('type'); // registration_bonus, waste_deposit, scan_bonus, pickup_reward, dropoff_bonus, reward_redeem, marketplace_purchase
            $table->string('description');
            $table->integer('balance_after');
            $table->timestamps();
        });

        // 2. Waste AI Analyses
        Schema::create('waste_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('material_name');
            $table->string('category'); // Plastik, Kertas, Organik, Logam, Kaca, E-Waste
            $table->integer('confidence_rate')->default(94);
            $table->integer('contamination_rate')->default(5);
            $table->decimal('estimated_price_per_kg', 12, 2)->default(4500);
            $table->decimal('weight_kg', 8, 2)->default(1.0);
            $table->boolean('has_scale_photo')->default(false);
            $table->decimal('total_estimated_value', 12, 2)->default(4500);
            $table->text('recommendation')->nullable();
            $table->string('status')->default('analyzed'); // analyzed, scheduled_pickup, dropped_off
            $table->timestamps();
        });

        // 3. On-Demand Pickup Requests
        Schema::create('pickup_requests', function (Blueprint $table) {
            $table->id();
            $table->string('pickup_code')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->json('categories');
            $table->date('scheduled_date');
            $table->string('scheduled_slot'); // 'pagi' (08:00 - 11:00), 'siang' (13:00 - 16:00)
            $table->text('address');
            $table->text('notes')->nullable();
            $table->decimal('estimated_weight', 8, 2)->default(5.0);
            $table->decimal('base_fee', 10, 2)->default(10000);
            $table->decimal('volume_surcharge', 10, 2)->default(0);
            $table->decimal('distance_km', 5, 2)->default(3.0);
            $table->decimal('distance_fee', 10, 2)->default(6000);
            $table->decimal('service_fee', 10, 2)->default(2000);
            $table->decimal('total_fee', 10, 2)->default(18000);
            $table->string('status')->default('confirmed'); // requested, confirmed, driver_assigned, on_the_way, arrived, completed
            $table->string('courier_name')->nullable();
            $table->string('courier_plate')->nullable();
            $table->string('courier_phone')->nullable();
            $table->timestamps();
        });

        // 4. Drop-off Depot Partners
        Schema::create('dropoff_partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('city');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('phone');
            $table->string('operating_hours');
            $table->boolean('is_open')->default(true);
            $table->boolean('is_premium_partner')->default(false);
            $table->integer('bonus_points')->default(20);
            $table->json('accepted_materials');
            $table->timestamps();
        });

        // 5. Rewards
        Schema::create('rewards', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category'); // e_wallet, voucher, donasi
            $table->string('provider');
            $table->integer('points_required');
            $table->decimal('value_idr', 12, 2);
            $table->integer('stock')->default(100);
            $table->text('description');
            $table->string('badge_text')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();
        });

        // 6. Reward Redemptions
        Schema::create('reward_redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reward_id')->constrained()->cascadeOnDelete();
            $table->integer('points_spent');
            $table->string('redemption_code');
            $table->string('status')->default('active'); // active, redeemed
            $table->timestamps();
        });

        // 7. Marketplace Raw Material Products
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

        // 8. Marketplace Orders
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
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketplace_orders');
        Schema::dropIfExists('marketplace_products');
        Schema::dropIfExists('reward_redemptions');
        Schema::dropIfExists('rewards');
        Schema::dropIfExists('dropoff_partners');
        Schema::dropIfExists('pickup_requests');
        Schema::dropIfExists('waste_analyses');
        Schema::dropIfExists('eco_point_transactions');
    }
};
