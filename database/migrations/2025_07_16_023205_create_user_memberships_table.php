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
        Schema::create('user_memberships', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->nullable();
            $table->integer('user_id');
            $table->integer('product_id');
            $table->string('unique_code')->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->decimal('total_price', 10, 2)->default(0.00);
            $table->integer('quota')->nullable();
            $table->string('payment_method')->nullable(); 
            $table->string('payment_proof')->nullable(); // path to payment proof file
            $table->string('payment_status')->default('unpaid'); // unpaid, paid
            $table->timestamp('paid_at')->nullable(); // timestamp when payment was made
            $table->integer('confirmed_by')->nullable();
            $table->timestamp('confirmed_at')->nullable(); // timestamp when payment was made
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
        Schema::dropIfExists('user_memberships');
    }
};
