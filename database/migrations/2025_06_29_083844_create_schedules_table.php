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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->date('schedule_date');
            $table->integer('group_class_id')->nullable();
            $table->string('group_class_name', 100)->nullable();
            $table->integer('class_id');
            $table->integer('trainer_id');
            $table->integer('level_class_id')->nullable();
            $table->string('level_class', 50)->nullable();
            $table->integer('time_id');
            $table->time('time');
            $table->integer('duration')->nullable();
            $table->integer('quota');
            $table->integer('quota_book')->default(0);
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
        Schema::dropIfExists('schedules');
    }
};
