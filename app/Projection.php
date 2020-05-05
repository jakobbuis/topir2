<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

abstract class Projection extends Model
{
    abstract public static function updateProjection(Event $event): void;

    public static function rehydrate(): void
    {
        static::truncate();
        $events = Event::all()->each(function ($event) {
            static::updateProjection($event);
        });
    }
}
