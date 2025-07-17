<?php

namespace Paparee\BaleInv\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Paparee\BaleInv\App\Models\Inventory;
use Paparee\BaleInv\App\Models\InventoryAssignment;

class InventoryMovement extends Model
{
    use HasUuids;

    protected $guarded = ['id'];
    protected $connection = 'inv';

    protected static function booted()
    {
        static::creating(function ($inventory_movement) {
            $inventory_movement->user_uuid = Auth::user()->uuid;
        });
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(InventoryAssignment::class);
    }

    public function getItemNameAttribute()
    {
        return $this->inventory?->item_name;
    }

    public function getUserAttribute()
    {
        return app('App\Models\User')::on('mysql')->where('uuid', $this->user_uuid)->first();
    }
}
