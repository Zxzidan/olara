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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rewards');
    }
};
