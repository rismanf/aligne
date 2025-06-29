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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('code_transaction', 20);
            $table->integer('amount');
            $table->string('status', 20);
            $table->integer('product_id');
            $table->integer('user_id');
            $table->date('send_date')->nullable();
            $table->text('file_evidence')->nullable();
            $table->date('approve_date')->nullable();
            $table->integer('approve_by_id')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('transactions');
    }
};
