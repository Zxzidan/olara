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
        Schema::create('eco_point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('amount'); // e.g. +50, -200
            $table->string('type'); // registration_bonus, waste_deposit, scan_bonus, pickup_reward, dropoff_bonus, reward_redeem, marketplace_purchase
            $table->string('description');
            $table->integer('balance_after');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eco_point_transactions');
    }
};
