<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class Counts extends Projection
{
    public static function updateProjection(Event $event): void
    {
        if ($event->data->event_name === 'item:completed') {
            $date = (new Carbon($event->data->event_data->date_completed))->format('Y-m-d');
            $entry = Counts::firstOrCreate(['date' => $date]); // ensure the record exists
            $entry->{self::priorityToFieldName($event->data->event_data->priority)} += 1;
            $entry->save();
            Log::debug('Count completed task', ['date' => $date, 'task' => $event->data->event_data->content]);
        }

        if ($event->data->event_name === 'item:uncompleted') {
            $date = (new Carbon($event->data->event_data->date_uncompleted))->format('Y-m-d');
            $entry = Counts::firstOrCreate(['date' => $date]); // ensure the record exists
            $entry->{self::priorityToFieldName($event->data->event_data->priority)} -= 1;
            $entry->save();
            Log::debug('Count minus one completed task', ['date' => $date, 'task' => $event->data->event_data->content]);
        }

        if ($event->data->event_name === 'topir:migration') {
            $entry = Counts::firstOrCreate(['date' => $event->data->date]);
            $entry->completed_p4 += $event->data->completed;
            $entry->save();
            Log::debug('Import existing statistic', ['date' => $event->data->date, 'completed' => $event->data->completed]);
        }
    }

    public static function last30DaysP1(): array
    {
        $day = Carbon::now()->subDays(29)->startOfday();
        $today = Carbon::now()->startOfday();

        $results = [];
        while ($day <= $today) {
            $entry = Counts::where('date', $day->format('Y-m-d'))->first();
            $results[] = $entry->completed_p1 ?? 0;
            $day->addDay();
        }

        return $results;
    }

    public static function last30DaysP2(): array
    {
        $day = Carbon::now()->subDays(29)->startOfday();
        $today = Carbon::now()->startOfday();

        $results = [];
        while ($day <= $today) {
            $entry = Counts::where('date', $day->format('Y-m-d'))->first();
            $results[] = $entry->completed_p2 ?? 0;
            $day->addDay();
        }

        return $results;
    }

    public static function last30DaysP3(): array
    {
        $day = Carbon::now()->subDays(29)->startOfday();
        $today = Carbon::now()->startOfday();

        $results = [];
        while ($day <= $today) {
            $entry = Counts::where('date', $day->format('Y-m-d'))->first();
            $results[] = $entry->completed_p3 ?? 0;
            $day->addDay();
        }

        return $results;
    }

    public static function last30DaysP4(): array
    {
        $day = Carbon::now()->subDays(29)->startOfday();
        $today = Carbon::now()->startOfday();

        $results = [];
        while ($day <= $today) {
            $entry = Counts::where('date', $day->format('Y-m-d'))->first();
            $results[] = $entry->completed_p4 ?? 0;
            $day->addDay();
        }

        return $results;
    }

    private static function priorityToFieldName(int $priority): string
    {
        return match ($priority) {
            1 => 'completed_p4',
            2 => 'completed_p3',
            3 => 'completed_p2',
            4 => 'completed_p1',
            default => throw new RuntimeException("Unknown priority level {$priority}"),
        };
    }
}
