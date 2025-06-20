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
        Schema::connection('inv')->create('inventory_movements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('inventory_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['return', 'distribution', 'adjustment', 'opname']);
            $table->enum('direction', ['in', 'out']); // arah perubahan stok
            $table->integer('quantity'); // selalu positif
            $table->text('note')->nullable();
            $table->uuid('user_uuid')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('inv')->dropIfExists('inventory_movements');
    }
};
