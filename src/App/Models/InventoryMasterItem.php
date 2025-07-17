<?php

namespace Paparee\BaleInv\App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class InventoryMasterItem extends Model
{
    use HasUuids;

    protected $guarded = ['id'];
    protected $connection = 'inv';

    public static function category()
    {
        $category = collect([
            ['name' => 'hardware'],
            ['name' => 'software'],
            ['name' => 'other'],
        ]);

        return $category;
    }

    // protected function specification(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn (string $value) => $value ? json_encode($value) : '',
    //         set: fn (string $value) => $value ? json_decode($value) : '',
    //     );
    // }
}
