<?php

namespace App\Console\Commands;

use App\Counts;
use App\Overdue;
use Illuminate\Console\Command;

class RehydrateProjections extends Command
{
    protected $signature = 'projections:rehydrate';

    protected $description = 'Rehydrate all projections';

    public function handle()
    {
        Counts::rehydrate();
        Overdue::rehydrate();
    }
}
