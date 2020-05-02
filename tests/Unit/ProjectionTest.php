<?php

namespace Tests\Unit;

use App\Counts;
use App\Event;
use Carbon\Carbon;
use Tests\TestCase;

class ProjectionTest extends TestCase
{
    /** @test */
    public function itKeepsAProjectionOfDailyCompletedTasks()
    {
        Carbon::setTestNow('03-01-2020');

        Event::create(['data' => (object) ['date_added' => '2020-01-01T08:00:00Z', 'event_name' => 'item:completed']]);
        Event::create(['data' => (object) ['date_added' => '2020-01-02T08:00:00Z', 'event_name' => 'item:completed']]);
        Event::create(['data' => (object) ['date_added' => '2020-01-02T08:00:00Z', 'event_name' => 'item:completed']]);

        $this->assertEquals(
            [
                '2019-12-04' => 0,
                '2019-12-05' => 0,
                '2019-12-06' => 0,
                '2019-12-07' => 0,
                '2019-12-08' => 0,
                '2019-12-09' => 0,
                '2019-12-10' => 0,
                '2019-12-11' => 0,
                '2019-12-12' => 0,
                '2019-12-13' => 0,
                '2019-12-14' => 0,
                '2019-12-15' => 0,
                '2019-12-16' => 0,
                '2019-12-17' => 0,
                '2019-12-18' => 0,
                '2019-12-19' => 0,
                '2019-12-20' => 0,
                '2019-12-21' => 0,
                '2019-12-22' => 0,
                '2019-12-23' => 0,
                '2019-12-24' => 0,
                '2019-12-25' => 0,
                '2019-12-26' => 0,
                '2019-12-27' => 0,
                '2019-12-28' => 0,
                '2019-12-29' => 0,
                '2019-12-30' => 0,
                '2019-12-31' => 0,
                '2020-01-01' => 1,
                '2020-01-02' => 2,
                '2020-01-03' => 0,
            ],
            Counts::last30Days()
        );
    }

    /** @test */
    public function itCanRehydrateTheProjection()
    {
        Carbon::setTestNow('03-01-2020');

        \Illuminate\Support\Facades\Event::fake();

        Event::create(['data' => (object) ['date_added' => '2020-01-02T08:00:00Z', 'event_name' => 'item:completed']]);
        Event::create(['data' => (object) ['date_added' => '2020-01-02T08:00:00Z', 'event_name' => 'item:completed']]);

        $this->assertEquals(0, Counts::count());

        Counts::rehydrate();

        $this->assertEquals(1, Counts::where('date', '2020-01-02')->count());
        $this->assertEquals(2, Counts::where('date', '2020-01-02')->first()->completed);
    }
}
