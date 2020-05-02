<?php

namespace App;

use App\Event;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Counts extends Model
{
    public static function updateProjection(Event $event): void
    {
        if ($event->data->event_name !== 'item:completed') {
            return;
        }

        $date = (new Carbon($event->data->date_added))->format('Y-m-d');
        $entry = Counts::firstOrCreate(['date' => $date]); // ensure the record exists
        $entry->completed += 1;
        $entry->save();
    }

    public static function last30Days(): array
    {
        $day = Carbon::now()->subDays(30)->startOfday();
        $today = Carbon::now()->startOfday();

        $results = [];
        while ($day <= $today) {
            $entry = Counts::where('date', $day->format('Y-m-d'))->first();
            $results[$day->format('Y-m-d')] = (int) ($entry->completed ?? 0);
            $day->addDay();
        }

        return $results;
    }
}
