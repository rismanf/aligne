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
        Schema::create('class_bookings', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id'); // Direct user reference
            $table->integer('user_membership_id')->nullable();
            $table->integer('class_schedule_id')->nullable();
            $table->enum('booking_status', ['pending', 'confirmed', 'cancelled'])->default('confirmed');
            $table->timestamp('booked_at')->default(now());
            $table->timestamp('cancelled_at')->nullable();
            $table->string('booking_code')->unique()->nullable(); // QR/Booking code
            $table->string('qr_token')->nullable(); // QR security token
            $table->enum('visit_status', ['pending', 'visited', 'no_show'])->default('pending');
            $table->timestamp('visited_at')->nullable();
            $table->integer('created_by_id')->nullable();
            $table->integer('updated_by_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for better performance
            $table->index(['user_id', 'booking_status']);
            $table->index(['class_schedule_id', 'visit_status']);
            $table->index('booking_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_bookings');
    }
};
