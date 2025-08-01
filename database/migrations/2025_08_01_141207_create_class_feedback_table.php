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
        Schema::create('class_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_booking_id')->constrained('class_bookings')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('class_schedule_id')->constrained('class_schedules')->onDelete('cascade');
            $table->tinyInteger('rating')->unsigned()->comment('1-5 stars');
            $table->text('comment')->nullable();
            $table->json('aspects')->nullable()->comment('Rating for specific aspects like instructor, facility, etc');
            $table->boolean('recommend')->default(true)->comment('Would recommend this class');
            $table->boolean('is_anonymous')->default(false);
            $table->boolean('is_approved')->default(true);
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();
            $table->unique('class_booking_id'); // One feedback per booking
            $table->index(['user_id', 'created_at']);
            $table->index(['class_schedule_id', 'rating']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_feedback');
    }
};
