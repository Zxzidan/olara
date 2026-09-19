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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dropoff_partners');
    }
};
