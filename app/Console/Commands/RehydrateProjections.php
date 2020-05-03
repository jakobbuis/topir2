<?php

namespace App\Console\Commands;

use App\Counts;
use Illuminate\Console\Command;

class RehydrateProjections extends Command
{
    protected $signature = 'projections:rehydrate';
    protected $description = 'Rehydrate all projections';

    public function handle()
    {
        Counts::rehydrate();
    }
}
