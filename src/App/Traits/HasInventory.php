<?php

namespace Paparee\BaleInv\App\Traits;

use Paparee\BaleInv\App\Models\Inventory;

trait HasInventory
{
    public static function bootHasInventory()
    {
        static::created(function ($model) {
            $model->inventory()->create(['stock' => 0]);
        });

        static::deleting(function ($model) {
            $model->inventory()->delete();
        });
    }

    public function inventory()
    {
        return $this->morphOne(Inventory::class, 'inventoryable');
    }

    public function increaseStock(int $quantity, ?string $note = null)
    {
        return $this->inventory->adjust($quantity, 'in', $note);
    }

    public function decreaseStock(int $quantity, ?string $note = null)
    {
        return $this->inventory->adjust($quantity, 'out', $note);
    }

    public function distributeStock(int $quantity, $contact_location = null, $assign_contact = null, string $condition, $status = null, ?string $note = null)
    {
        return $this->inventory->distribute($quantity, $contact_location, $assign_contact, $condition, $status, $note);
    }

    public function returnItem(?string $note = null)
    {
        return $this->inventory->return($note);
    }

    public function setStock(int $quantity, ?string $note = null)
    {
        return $this->inventory->opname($quantity, $note);
    }
}

