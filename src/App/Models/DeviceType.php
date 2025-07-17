<?php

namespace Paparee\BaleInv\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class DeviceType extends Model
{
    use HasUuids;

    protected $guarded = ['id'];
    protected $connection = 'inv';
}
