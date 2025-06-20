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
        Schema::connection('inv')->create('inventory_assignments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('inventory_movement_id');
            $table->string('inv_code')->nullable();
            $table->string('contact_location', 50)->nullable();
            $table->enum('type', ['distribution', 'return']);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->jsonb('assign_contact')->nullable();
            $table->string('assign_condition')->nullable();
            $table->dateTime('assigned_at')->nullable();
            $table->string('return_condition')->nullable();
            $table->dateTime('returned_at')->nullable();
            $table->boolean('is_returned')->default(0);
            $table->string('status')->nullable();
            $table->uuid('user_uuid')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('inv')->dropIfExists('inventory_assignments');
    }
};
