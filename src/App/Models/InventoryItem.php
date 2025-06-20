<?php

namespace Paparee\BaleInv\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Paparee\BaleInv\App\Traits\HasInventory;

class InventoryItem extends Model
{
    use HasUuids;
    use HasInventory;

    protected $guarded = ['id'];
    protected $connection = 'inv';

    public static function category()
    {
        $category = collect([
            ['name' => 'hardware'],
            ['name' => 'software'],
        ]);

        return $category;
    }

    public function inventory()
    {
        return $this->morphOne(Inventory::class, 'inventoryable');
    }

}
