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
        Schema::create('inventory_devices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('device_name');
            $table->string('serial_number')->nullable()->unique();
            $table->string('ip_address')->nullable()->unique();
            $table->string('mac_address')->nullable()->unique();
            $table->string('device_type')->nullable(); // laptop, router, printer, etc.
            $table->string('model')->nullable();
            $table->string('brand')->nullable();
            $table->jsonb('specification')->nullable();
            $table->enum('status', ['available', 'in_use', 'retired', 'broken'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_devices');
    }
};
