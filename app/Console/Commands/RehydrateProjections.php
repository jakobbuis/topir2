<?php

namespace App\Console\Commands;

use App\Models\Counts;
use App\Models\Overdue;
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
