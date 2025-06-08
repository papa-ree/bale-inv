<?php

namespace Paparee\BaleInv\App\Traits;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Paparee\BaleInv\App\Models\Inventory;

trait HasInventory
{
    public function inventory(): MorphOne
    {
        return $this->morphOne(Inventory::class, 'inventoryable');
    }
}
