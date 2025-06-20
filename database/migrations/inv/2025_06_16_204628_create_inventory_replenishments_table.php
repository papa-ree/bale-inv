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
        Schema::connection('inv')->create('inventory_replenishments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('inventory_item_id');
            $table->date('purchase_date')->nullable();
            $table->integer('min_stock_level')->nullable();
            $table->string('supplier')->nullable();
            $table->string('supplier_contact')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('inv')->dropIfExists('inventory_replenishments');
    }
};
