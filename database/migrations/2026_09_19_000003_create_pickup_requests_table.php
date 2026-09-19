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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pickup_requests');
    }
};
