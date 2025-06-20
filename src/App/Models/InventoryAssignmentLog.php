<?php

namespace Paparee\BaleInv\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryAssignmentLog extends Model
{
    use HasUuids;

    protected $guarded = ['id'];
    protected $connection = 'inv';

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(InventoryAssignment::class);
    }
}
