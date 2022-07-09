<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

abstract class Projection extends Model
{
    abstract public static function updateProjection(Event $event): void;

    public static function rehydrate(): void
    {
        // As all projections go back max 30 days, we need look back no further
        $beginningOfTime = Carbon::now()->subDays(35);

        static::truncate();

        foreach (Event::where('created_at', '>=', $beginningOfTime)->lazy() as $event) {
            static::updateProjection($event);
        }
    }
}
