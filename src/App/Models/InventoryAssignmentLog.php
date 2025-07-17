<?php

namespace Paparee\BaleInv\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class InventoryAssignmentLog extends Model
{
    use HasUuids;

    protected $guarded = ['id'];
    protected $connection = 'inv';

    protected static function booted()
    {
        static::creating(function ($inventory_assignment_log) {
            $inventory_assignment_log->user_uuid = Auth::user()->uuid;
        });
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(InventoryAssignment::class);
    }
}
