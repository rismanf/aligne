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
        Schema::create('contact_us', function (Blueprint $table) {
            $table->id();
            $table->string('source')->nullable();
            $table->string('full_name');
            $table->string('fist_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('company');
            $table->string('job_title');
            $table->string('country');            
            $table->string('subject')->nullable();
            $table->string('message');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_us');
    }
};
