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
        Schema::connection('inv')->create('inventory_assignment_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('inventory_assignment_id');
            $table->string('type')->nullable();
            $table->string('location')->nullable();
            $table->string('contact')->nullable();
            $table->string('condition')->nullable();
            $table->string('status')->nullable();
            $table->string('user_uuid')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('inv')->dropIfExists('inventory_assignment_logs');
    }
};
