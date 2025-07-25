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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();// unique slug for the news article
            $table->string('title_slug');
            $table->string('title');
            $table->string('description')->nullable();           
            $table->string('keywords')->nullable();
            $table->string('author')->nullable();
            $table->string('image_original')->nullable();
            $table->string('image_medium')->nullable();
            $table->string('image_small')->nullable();
            $table->string('category')->nullable();
            $table->string('tags')->nullable();
            $table->text('body');            
            $table->date('published_at')->nullable();
            $table->integer('month')->nullable();
            $table->integer('year')->nullable();
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
        Schema::dropIfExists('news');
    }
};
