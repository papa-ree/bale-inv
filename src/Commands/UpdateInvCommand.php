<?php

namespace Paparee\BaleInv\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'bale-inv:update', hidden: true)]
class UpdateInvCommand extends Command
{
    protected $signature = 'bale-inv:update';
    protected $description = 'Force update bale-inv from vendor';

    public function handle()
    {
        $this->info('Publishing views...');
        $this->info('Publishing migrations...');

        $this->call('vendor:publish', [
            '--tag' => 'bale-inv-views',
            '--force' => true,
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'bale-inv-migrations',
            '--force' => true,
        ]);

        $this->info('Bale iNV updated successfully.');
    }
}
