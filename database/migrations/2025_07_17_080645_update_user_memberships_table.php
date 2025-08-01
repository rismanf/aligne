<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_memberships', function (Blueprint $table) {
            // Add new columns for enhanced membership system
            $table->string('status')->default('pending')->after('payment_status'); // pending, active, expired, suspended
            $table->timestamp('starts_at')->nullable()->after('status');
            $table->timestamp('expires_at')->nullable()->after('starts_at');
        });

        // Rename product_id to membership_id using raw SQL to avoid issues
        DB::statement('ALTER TABLE user_memberships CHANGE product_id membership_id INT');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_memberships', function (Blueprint $table) {
            $table->dropColumn(['status', 'starts_at', 'expires_at']);
        });
        
        // Rename back to product_id
        DB::statement('ALTER TABLE user_memberships CHANGE membership_id product_id INT');
    }
};
