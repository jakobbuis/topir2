<?php

namespace App\Listeners;

use App\Counts;
use App\Events\EventCreated;

class UpdateProjections
{
    public function handle(EventCreated $event)
    {
        Counts::updateProjection($event->event);
    }
}
