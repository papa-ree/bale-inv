<?php

namespace Paparee\BaleInv\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Paparee\BaleInv\BaleInv
 */
class BaleInv extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Paparee\BaleInv\BaleInv::class;
    }
}
