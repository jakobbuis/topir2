<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Overdue extends Model
{
    protected $table = 'overdue';

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
