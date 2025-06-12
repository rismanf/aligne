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
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->integer('event_id');
            $table->string('full_name');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('job_title')->nullable();
            $table->string('company')->nullable();
            $table->string('country')->nullable();
            $table->string('type_user')->nullable();
            $table->string('invoice_code')->nullable();
            $table->string('coupon_code')->nullable();
            $table->string('price')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('participants');
    }
};
