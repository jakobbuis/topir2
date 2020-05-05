<?php

namespace App\Listeners;

use App\Counts;
use App\Events\EventCreated;
use App\Overdue;

class UpdateProjections
{
    public function handle(EventCreated $event)
    {
        Counts::updateProjection($event->event);
        Overdue::updateProjection($event->event);
    }
}
