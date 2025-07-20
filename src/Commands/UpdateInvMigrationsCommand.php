<?php

namespace Paparee\BaleInv\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'inv:update-migration', hidden: true)]
class UpdateInvMigrationsCommand extends Command
{
    protected $signature = 'inv:update-migration';
    protected $description = 'Force update bale-inv migration from vendor';

    public function handle()
    {
        $this->info('Publishing migrations...');

        $this->call('vendor:publish', [
            '--tag' => 'bale-inv-migrations',
            '--force' => true,
        ]);

        $this->info('Migrations updated successfully.');
    }
}
