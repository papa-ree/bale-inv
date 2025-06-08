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
        Schema::create('item_locations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('inventory_item_location_name');
            $table->text('inventory_item_location_address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->uuidMorphs('locationable'); // locationable_id, locationable_type
            $table->timestamps();
        });
    }
// $d->assignLocation(['item_location_naem' => 'Gudang IT Lantai 2', 'inventory_item_location_address' => 'Jl. Contoh No. 12','latitude' => '-6.1751', 'longitde' => '106.8650'])
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_locations');
    }
};
