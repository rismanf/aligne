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
        Schema::create('log_logins', function (Blueprint $table) {
            $table->id();
            $table->string('event_name', 20);
            $table->string('status', 50);
            $table->timestamp('event_time', 20);
            $table->integer('user_id')->nullable();
            $table->string('email', 30);            
            $table->text('status_description')->nullable();
            $table->string('ip_address', 50);
            $table->string('device', 50);
            $table->string('os', 50);
            $table->string('browser', 50);
            $table->text('extra_info')->nullable();
            $table->timestamps();
        });

        Schema::create('log_systems', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 20)->nullable();
            $table->string('ip', 20);
            $table->string('event', 200)->nullable();
            $table->string('extra')->nullable();
            $table->text('additional')->nullable();
            $table->timestamps();
        });

        Schema::create('master_data', function (Blueprint $table) {
            $table->id();
            $table->string('type',75);
            $table->string('code',75);
            $table->string('name')->nullable();
            $table->string('parent_code')->nullable();
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_logins');
        Schema::dropIfExists('log_systems');
        Schema::dropIfExists('master_data');
    }
};
