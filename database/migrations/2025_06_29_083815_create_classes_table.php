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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->integer('group_class_id')->nullable();
            $table->string('group_class', 100)->nullable();
            $table->string('name', 200);
            $table->integer('level_class_id')->nullable();
            $table->string('level_class', 50)->nullable();
            $table->integer('mood_class_id')->nullable();
            $table->string('mood_class', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('image_original')->nullable();
            $table->boolean('is_active')->default(1);
            $table->integer('created_by_id')->nullable();
            $table->integer('updated_by_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
