<?php

namespace App;

use Carbon\Carbon;

class Counts extends Projection
{
    public static function updateProjection(Event $event): void
    {
        if ($event->data->event_name === 'item:completed') {
            $date = (new Carbon($event->data->event_data->date_completed))->format('Y-m-d');
            $entry = Counts::firstOrCreate(['date' => $date]); // ensure the record exists
            $entry->completed += 1;
            $entry->completed_p1 += (int) $event->data->event_data->priority === 4;
            $entry->save();
        }

        if ($event->data->event_name === 'item:uncompleted') {
            $date = (new Carbon($event->data->event_data->date_uncompleted))->format('Y-m-d');
            $entry = Counts::firstOrCreate(['date' => $date]); // ensure the record exists
            $entry->completed -= 1;
            $entry->save();
        }

        if ($event->data->event_name === 'topir:migration') {
            $entry = Counts::firstOrCreate(['date' => $event->data->date]);
            $entry->completed += $event->data->completed;
            $entry->save();
        }
    }

    public static function last30Days(): array
    {
        $day = Carbon::now()->subDays(29)->startOfday();
        $today = Carbon::now()->startOfday();

        $results = [];
        while ($day <= $today) {
            $entry = Counts::where('date', $day->format('Y-m-d'))->first();
            $results[] = (int) ($entry->completed ?? 0);
            $day->addDay();
        }

        return $results;
    }

    public static function last30DaysP1(): array
    {
        $day = Carbon::now()->subDays(29)->startOfday();
        $today = Carbon::now()->startOfday();

        $results = [];
        while ($day <= $today) {
            $entry = Counts::where('date', $day->format('Y-m-d'))->first();
            $results[] = (int) ($entry->completed_p1 ?? 0);
            $day->addDay();
        }

        return $results;
    }
}
