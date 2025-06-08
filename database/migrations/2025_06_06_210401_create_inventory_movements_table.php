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
        Schema::create('inventory_movements', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->uuid('inventory_id'); // referensi ke tabel inventories
        $table->enum('type', ['in', 'out', 'adjustment', 'opname']);
        $table->integer('quantity'); // positif (masuk), negatif (keluar)
        $table->text('description')->nullable();
        $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamps();

        $table->foreign('inventory_id')->references('id')->on('inventories')->cascadeOnDelete();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
