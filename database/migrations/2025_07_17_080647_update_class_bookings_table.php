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
        Schema::table('class_bookings', function (Blueprint $table) {
            // Add user_id for direct user reference
            $table->integer('user_id')->after('id');
            
            // Add booking status and timestamps
            $table->enum('booking_status', ['pending', 'confirmed', 'cancelled'])->default('confirmed')->after('class_schedule_id');
            $table->timestamp('booked_at')->default(now())->after('booking_status');
            $table->timestamp('cancelled_at')->nullable()->after('booked_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_bookings', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'booking_status', 'booked_at', 'cancelled_at']);
        });
    }
};
