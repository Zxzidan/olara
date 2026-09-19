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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waste_analyses');
    }
};
