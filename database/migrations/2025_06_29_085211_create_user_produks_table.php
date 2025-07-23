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
        Schema::create('user_produks', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->nullable();
            $table->integer('user_id');
            $table->integer('product_id');
            $table->string('unique_code')->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->decimal('total_price', 10, 2)->default(0.00);
            $table->integer('kuota')->nullable();
            $table->string('payment_method')->nullable(); // e.g., bank transfer, credit card
            $table->string('payment_proof')->nullable(); // unpaid, paid
            $table->string('payment_status')->default('unpaid'); // unpaid, paid
            $table->timestamp('paid_at')->nullable(); // timestamp when payment was made
            $table->integer('confirmed_by')->nullable();
            $table->timestamp('confirmed_at')->nullable(); // timestamp when payment was made
            $table->boolean('is_active')->default(0);
            $table->integer('created_by_id')->nullable();
            $table->integer('updated_by_id')->nullable();
            $table->timestamps();
            $table->softDeletes(); // for soft delete functionality
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_produks');
    }
};
