<?php

namespace App\Console\Commands;

use App\Models\Link;
use Illuminate\Console\Command;

class ResetClicks extends Command
{
    protected $signature = 'reset:clicks';
    protected $description = 'Reset clicks for all links';

    public function handle()
    {
        Link::query()->update(['clicks' => 0]);
        $this->info('All link clicks have been reset.');
        echo ("Links Cleaned");
    }
}
