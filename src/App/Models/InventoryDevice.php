<?php

namespace Paparee\BaleInv\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Paparee\BaleInv\App\Traits\HasInventory;
use Paparee\BaleInv\App\Traits\HasLocation;

class InventoryDevice extends Model
{
    use HasUuids;
    use HasInventory;
    use HasLocation;

    protected $guarded = ['id'];
}
