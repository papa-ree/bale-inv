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
        Schema::connection('inv')->create('inventory_assignment_item_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('inventory_assignment_id');
            $table->string('item_name_alias')->nullable();
            $table->string('device_type')->nullable()->comment('Router, Access Point, Printer, Computer, Switch');
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->macAddress('mac_address')->nullable();
            $table->string('brand')->nullable();
            $table->jsonb('specification')->nullable();
            $table->enum('license_type', ['lifetime', 'subscription'])->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('inv')->dropIfExists('inventory_assignment_item_details');
    }
};
