<?php

namespace App\Events;

use App\Event;
use Illuminate\Queue\SerializesModels;

class EventCreated
{
    public $event;

    use SerializesModels;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }
}
