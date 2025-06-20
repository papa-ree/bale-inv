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
        Schema::connection('inv')->create('inventory_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('inventory_item_name')->unique();
            $table->string('inventory_item_type')->comment('set hardware or software');
            $table->string('inventory_item_brand')->nullable();
            $table->boolean('is_device')->nullable();
            $table->string('inventory_item_device_type')->comment('router, switch, computer, etc')->nullable();
            $table->string('inventory_item_license_purpose')->comment('networking, software, computer, etc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('inv')->dropIfExists('inventory_items');
    }
};
