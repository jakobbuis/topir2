<?php

namespace Tests\Unit;

use App\Models\Counts;
use App\Models\Event;
use Carbon\Carbon;
use Tests\TestCase;

class CountsProjectionTest extends TestCase
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
            Counts::last30DaysP4()
        );
    }

    /** @test */
    public function itCanRehydrateTheProjection()
    {
        Carbon::setTestNow('03-01-2020');

        // Prevent premature hydration
        \Illuminate\Support\Facades\Event::fake();

        $this->createEvent('2020-01-02', 4);
        $this->createEvent('2020-01-02', 3);
        $this->createEvent('2020-01-02', 2);
        $this->createEvent('2020-01-02', 1);
        $this->createEvent('2020-01-02', 1);

        $this->createEvent('2020-01-03', 1);

        $this->assertEquals(0, Counts::count());

        Counts::rehydrate();

        $this->assertEquals(1, Counts::where('date', '2020-01-02')->count());
        $dayOne = Counts::where('date', '2020-01-02')->first();
        $this->assertEquals(1, $dayOne->completed_p1);
        $this->assertEquals(1, $dayOne->completed_p2);
        $this->assertEquals(1, $dayOne->completed_p3);
        $this->assertEquals(2, $dayOne->completed_p4);

        $this->assertEquals(1, Counts::where('date', '2020-01-03')->count());
        $dayOne = Counts::where('date', '2020-01-03')->first();
        $this->assertEquals(0, $dayOne->completed_p1);
        $this->assertEquals(0, $dayOne->completed_p2);
        $this->assertEquals(0, $dayOne->completed_p3);
        $this->assertEquals(1, $dayOne->completed_p4);
    }

    /** @test */
    public function rehydrationResetsTheProjection()
    {
        Carbon::setTestNow('03-01-2020');

        $this->createEvent('2020-01-02', 1);
        $this->createEvent('2020-01-02', 4);

        $this->assertEquals(1, Counts::count());

        Event::all()->each->delete();

        Counts::rehydrate();

        $this->assertEquals(0, Counts::count());
    }

    /** @test */
    public function itemsCanBeUncompleted()
    {
        Carbon::setTestNow('03-01-2020');

        $this->createEvent('2020-01-01', 4);
        $this->createEvent('2020-01-01', 4);

        $this->assertCount(1, Counts::all());
        $this->assertEquals(2, Counts::first()->completed_p1);

        Event::create([
            'data' => (object) [
                'event_data' => (object) [
                    'date_uncompleted' => '2020-01-01T08:00:00Z',
                    'priority' => 4,
                    'content' => 'Some foo task',
                ],
                'event_name' => 'item:uncompleted',
            ],
        ]);

        $this->assertEquals(1, Counts::first()->completed_p1);
    }

    private function createEvent(string $date, int $priority): void
    {
        Event::create([
            'data' => (object) [
                'event_data' => (object) [
                    'date_completed' => $date . 'T08:00:00Z',
                    'priority' => $priority,
                    'content' => 'Some foo task',
                ],
                'event_name' => 'item:completed',
            ],
        ]);
    }
}
