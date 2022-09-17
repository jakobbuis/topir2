<?php

namespace App\Listeners;

use App\Models\Counts;
use App\Events\EventCreated;
use App\Models\Overdue;

class UpdateProjections
{
    public function handle(EventCreated $event)
    {
        Counts::updateProjection($event->event);
        Overdue::updateProjection($event->event);
    }
}
