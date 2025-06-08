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
        Schema::create('inventory_software', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('version')->nullable();
            $table->string('license_key')->nullable();
            $table->enum('license_type', ['lifetime', 'subscription'])->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->integer('total_seat')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_software');
    }
};
