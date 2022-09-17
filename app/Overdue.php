<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Overdue extends Projection
{
    protected $table = 'overdue';

    public static function updateProjection(Event $event): void
    {
        if (in_array($event->data->event_name, ['topir:overdue-count', 'topir:migration'])) {
            $date = (new Carbon($event->data->date))->format('Y-m-d');
            $entry = Overdue::firstOrCreate(['date' => $date]);
            $entry->count += $event->data->overdue;
            $entry->save();
            Log::debug('Count completed task', ['date' => $date, 'task' => $event->data->overdue]);
        }
    }

    public static function last30Days(): array
    {
        $day = Carbon::now()->subDays(29)->startOfday();
        $today = Carbon::now()->startOfday();

        $results = [];
        while ($day <= $today) {
            $entry = Overdue::where('date', $day->format('Y-m-d'))->first();
            $results[] = (int) ($entry->count ?? 0);
            $day->addDay();
        }

        return $results;
    }
}
