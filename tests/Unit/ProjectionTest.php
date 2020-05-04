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

        $this->createEvent('2020-01-01', 1);
        $this->createEvent('2020-01-02', 1);
        $this->createEvent('2020-01-02', 1);

        $this->assertEquals(
            [
                0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                0, 0, 0, 0, 0, 0, 0, 1, 2, 0,
            ],
            Counts::last30Days()
        );
    }

    /** @test */
    public function itCanRehydrateTheProjection()
    {
        Carbon::setTestNow('03-01-2020');

        // Prevent premature hydration
        \Illuminate\Support\Facades\Event::fake();

        $this->createEvent('2020-01-02', 1);
        $this->createEvent('2020-01-02', 1);

        $this->assertEquals(0, Counts::count());

        Counts::rehydrate();

        $this->assertEquals(1, Counts::where('date', '2020-01-02')->count());
        $this->assertEquals(2, Counts::where('date', '2020-01-02')->first()->completed);
    }

    /** @test */
    public function rehydrationResetsTheProjection()
    {
        Carbon::setTestNow('03-01-2020');

        $this->createEvent('2020-01-02', 1);
        $this->createEvent('2020-01-02', 2);

        $this->assertEquals(1, Counts::count());

        Event::all()->each->delete();

        Counts::rehydrate();

        $this->assertEquals(0, Counts::count());
    }

    /** @test */
    public function itRecordsP1CompletionsSeparately()
    {
        Carbon::setTestNow('03-01-2020');

        $this->createEvent('2020-01-01', 1);
        $this->createEvent('2020-01-01', 4);
        $this->createEvent('2020-01-02', 2);
        $this->createEvent('2020-01-02', 3);
        $this->createEvent('2020-01-02', 4);
        $this->createEvent('2020-01-02', 4);

        $this->assertEquals(
            [
                0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                0, 0, 0, 0, 0, 0, 0, 1, 2, 0,
            ],
            Counts::last30DaysP1()
        );
    }

    private function createEvent(string $date, int $priority): void
    {
        Event::create([
            'data' => (object) [
                'event_data' => (object) [
                    'date_completed' => $date . 'T08:00:00Z',
                    'priority' => $priority,
                ],
                'event_name' => 'item:completed',
            ],
        ]);
    }
}
