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
        Schema::table('group_classes', function (Blueprint $table) {
            // Add category and level columns (is_active already exists)
            $table->enum('category', ['reformer', 'chair', 'functional'])->default('reformer')->after('description');
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->default('beginner')->after('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('group_classes', function (Blueprint $table) {
            $table->dropColumn(['category', 'level']);
        });
    }
};
