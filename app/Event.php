<?php

namespace App;

use App\Events\EventCreated;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $casts = [
        'data' => 'object',
    ];

    protected $dispatchesEvents = [
        'created' => EventCreated::class,
    ];
}
