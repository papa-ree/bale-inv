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
        Schema::connection('inv')->create('inventory_master_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('item_name')->unique();
            $table->string('item_type')->comment('set hardware or software');
            $table->string('item_brand')->nullable();
            $table->boolean('is_device')->nullable();
            $table->boolean('is_consumable')->nullable();
            $table->text('specification')->nullable();
            $table->string('item_device_type')->comment('router, switch, computer, etc')->nullable();
            $table->string('item_license_purpose')->comment('networking, software, computer, etc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('inv')->dropIfExists('inventory_master_items');
    }
};
