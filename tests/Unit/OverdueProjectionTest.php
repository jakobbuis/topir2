<?php

namespace Tests\Unit;

use App\Event;
use App\Overdue;
use Carbon\Carbon;
use Tests\TestCase;

class OverdueProjectionTest extends TestCase
{
    /** @test */
    public function itKeepsALogOf30DaysPast()
    {
        Carbon::setTestNow('03-01-2020');

        $this->createEvent('2020-01-02', 2);
        $this->createEvent('2020-01-01', 1);

        $this->assertEquals(
            [
                0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                0, 0, 0, 0, 0, 0, 0, 1, 2, 0,
            ],
            Overdue::last30Days()
        );
    }

    /** @test */
    public function itCanRehydrateTheProjection()
    {
        Carbon::setTestNow('03-01-2020');

        // Prevent premature hydration
        \Illuminate\Support\Facades\Event::fake();

        $this->createEvent('2020-01-02', 16);

        $this->assertEquals(0, Overdue::count());

        Overdue::rehydrate();

        $this->assertEquals(1, Overdue::where('date', '2020-01-02')->count());
        $this->assertEquals(16, Overdue::where('date', '2020-01-02')->first()->count);
    }

    /** @test */
    public function rehydrationResetsTheProjection()
    {
        Carbon::setTestNow('03-01-2020');

        $this->createEvent('2020-01-02', 1);

        $this->assertEquals(1, Overdue::count());

        Event::all()->each->delete();

        Overdue::rehydrate();

        $this->assertEquals(0, Overdue::count());
    }

    /** @test */
    public function nullOverdueAreTreatedAsZero()
    {
        Carbon::setTestNow('03-01-2020');

        $this->createEvent('2020-01-02', 2);
        $this->createEvent('2020-01-01', null);

        $this->assertEquals(
            [
                0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                0, 0, 0, 0, 0, 0, 0, 0, 2, 0,
            ],
            Overdue::last30Days()
        );
    }

    private function createEvent(string $date, $overdueCount): void
    {
        Event::create([
            'data' => (object) [
                'event_name' => 'topir:overdue-count',
                'date' => $date,
                'overdue' => $overdueCount,
            ],
        ]);
    }
}
