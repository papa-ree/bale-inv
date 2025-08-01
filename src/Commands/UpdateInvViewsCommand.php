<?php

namespace Paparee\BaleInv\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'inv:update-view', hidden: true)]
class UpdateInvViewsCommand extends Command
{
    protected $signature = 'inv:update-view';
    protected $description = 'Force update bale-inv views from vendor';

    public function handle()
    {
        $this->info('Publishing views...');

        $this->call('vendor:publish', [
            '--tag' => 'bale-inv-views',
            '--force' => true,
        ]);

        $this->info('Views updated successfully.');
    }
}
