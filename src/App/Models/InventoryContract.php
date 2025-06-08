<?php

namespace Paparee\BaleInv\App\Models;

use Paparee\BaleInv\App\Traits\HasInventory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class InventoryContract extends Model
{
    use HasUuids;
    use HasInventory;

    protected $guarded = ['id'];
}
