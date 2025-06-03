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
        Schema::create('qoutations', function (Blueprint $table) {
            $table->id();
            $table->integer('event_id');
            $table->integer('type_user');
            $table->string('quotation')->nullable();
            $table->string('quotation_type')->nullable();
            $table->string('quotation_option')->nullable();
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
        Schema::dropIfExists('qoutations');
    }
};
