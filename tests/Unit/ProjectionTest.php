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

        Event::create(['data' => (object) ['event_data' => (object) [ 'date_completed' => '2020-01-01T08:00:00Z'], 'event_name' => 'item:completed']]);
        Event::create(['data' => (object) ['event_data' => (object) [ 'date_completed' => '2020-01-02T08:00:00Z'], 'event_name' => 'item:completed']]);
        Event::create(['data' => (object) ['event_data' => (object) [ 'date_completed' => '2020-01-02T08:00:00Z'], 'event_name' => 'item:completed']]);

        $this->assertEquals(
            [
                0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                0, 0, 0, 0, 0, 0, 0, 0, 1, 2,
            ],
            Counts::last30Days()
        );
    }

    /** @test */
    public function itCanRehydrateTheProjection()
    {
        Carbon::setTestNow('03-01-2020');

        \Illuminate\Support\Facades\Event::fake();

        Event::create(['data' => (object) ['event_data' => (object) [ 'date_completed' => '2020-01-02T08:00:00Z'], 'event_name' => 'item:completed']]);
        Event::create(['data' => (object) ['event_data' => (object) [ 'date_completed' => '2020-01-02T08:00:00Z'], 'event_name' => 'item:completed']]);

        $this->assertEquals(0, Counts::count());

        Counts::rehydrate();

        $this->assertEquals(1, Counts::where('date', '2020-01-02')->count());
        $this->assertEquals(2, Counts::where('date', '2020-01-02')->first()->completed);
    }
}
