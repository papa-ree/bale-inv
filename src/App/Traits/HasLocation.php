<?php

namespace Paparee\BaleInv\App\Traits;

use Paparee\BaleInv\App\Models\ItemLocation;

trait HasLocation
{
    // public function location(): BelongsTo
    // {
    //     return $this->belongsTo(ItemLocation::class, 'item_location_id');
    // }

    public function location()
    {
        return $this->morphOne(ItemLocation::class, 'locationable');
    }

    public function assignLocation(array $data)
    {
        return $this->location()->create($data);
    }

    public function updateLocation(array $data)
    {
        return $this->location()->updateOrCreate([], $data);
    }
}