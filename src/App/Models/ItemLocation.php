<?php

namespace Paparee\BaleInv\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ItemLocation extends Model
{
    use HasUuids;

    protected $guarded = ['id'];

    // public function model(): MorphTo
    // {
    //     return $this->morphTo();
    // }

    public function locationable(): MorphTo
    {
        return $this->morphTo();
    }
}
