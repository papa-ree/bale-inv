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
        Schema::connection('inv')->create('inventories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuidMorphs('inventoryable'); // polymorphic ke item
            $table->integer('stock')->default(0); // stok saat ini
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('inv')->dropIfExists('inventories');
    }
};
