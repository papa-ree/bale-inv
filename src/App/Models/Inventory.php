<?php

namespace Paparee\BaleInv\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Inventory extends Model
{
    use HasUuids;

    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function movements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function logMovement(int $quantity, string $type, ?string $description = null): void
    {
        $this->movements()->create([
            'quantity' => $quantity,
            'type' => $type,
            'description' => $description,
            'created_by' => auth()->uuid(),
        ]);
    }
}
