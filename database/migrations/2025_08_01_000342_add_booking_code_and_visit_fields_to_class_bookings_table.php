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
            $table->string('booking_code')->unique()->nullable()->after('booking_status');
            $table->enum('visit_status', ['pending', 'visited', 'no_show'])->default('pending')->after('booking_code');
            $table->timestamp('visited_at')->nullable()->after('visit_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_bookings', function (Blueprint $table) {
            $table->dropColumn(['booking_code', 'visit_status', 'visited_at']);
        });
    }
};
