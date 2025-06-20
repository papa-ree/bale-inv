<?php

namespace Paparee\BaleInv\App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryAssignment extends Model
{
    use HasUuids;

    protected $guarded = ['id'];
    protected $connection = 'inv';

    protected static function booted()
    {
        static::creating(function ($inventory_assignment) {
            $inventory_assignment->inv_code = date('Y') . '-' . uniqid();
        });
    }
    
    public static function conditions()
    {
        $conditions = collect([
            ['name' => 'good'],
            ['name' => 'slightly damaged'],
            ['name' => 'damaged']
        ]);

        return $conditions;
    }
    
    public static function statuses()
    {
        $conditions = collect([
            ['name' => 'available'],
            ['name' => 'active'],
            ['name' => 'installed'],
            ['name' => 'loaned'],
            ['name' => 'missing']
        ]);

        return $conditions;
    }

    public function movement()
    {
        return $this->belongsTo(InventoryMovement::class, 'inventory_movement_id');
    }

    protected function contactLocation(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? json_decode($value) : '-',
        );
    }
    
    protected function assignContact(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? json_decode($value) : '-',
        );
    }
    
    protected function assignedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::parse($value)->diffForHumans() : '-',
        );
    }
    
    protected function returnedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::parse($value)->diffForHumans() : '-',
        );
    }

    public function getItemNameAttribute()
    {
        return $this->movement->inventory->inventoryable->inventory_item_name ?? null;
    }

    public function getItemDataAttribute()
    {
        return $this->movement->inventory->inventoryable ?? null;
    }

    public function assignLog(): HasMany
    {
        return $this->hasMany(InventoryAssignmentLog::class);
    }

    
    public function getUserAttribute()
    {
        return app('App\Models\User')::on('mysql')->where('uuid', $this->user_uuid)->first();
    }

}
