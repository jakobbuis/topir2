<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Overdue;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CorrectOverdue extends Command
{
    protected $signature = 'overdue:correct {date} {overdue}';
    protected $description = 'Issues a correction event for the overdue count on the given date';

    public function handle()
    {
        $event = Event::create([
            'data' => [
                'event_name' => 'topir:overdue-correction',
                'date' => (new Carbon($this->argument('date')))->format('Y-m-d'),
                'overdue' => (int) $this->argument('overdue'),
            ],
        ]);

        Overdue::updateProjection($event);

        return Command::SUCCESS;
    }
}
