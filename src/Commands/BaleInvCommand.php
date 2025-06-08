<?php

namespace Paparee\BaleInv\Commands;

use Illuminate\Console\Command;

class BaleInvCommand extends Command
{
    public $signature = 'bale-inv';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
