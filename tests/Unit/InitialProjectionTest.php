<?php

namespace Tests\Unit;

use App\Models\Counts;
use App\Models\Event;
use App\Models\Overdue;
use Tests\TestCase;

class InitialProjectionTest extends TestCase
{
    /** @test */
    public function itSupportsACustomElementKnownAsInitialState()
    {
        Event::create([
            'data' => (object) [
                'event_name' => 'topir:migration',
                'date' => '2020-05-01',
                'completed' => 3,
                'overdue' => 0,
            ],
        ]);

        $this->assertEquals(3, Counts::where('date', '2020-05-01')->first()->completed_p4);
    }

    /** @test */
    public function migrationsAreAddedToTotals()
    {
        Event::create([
            'data' => (object) [
                'event_data' => (object) [
                    'date_completed' => '2020-05-01T08:00:00Z',
                    'priority' => 1,
                    'content' => 'Some foo task',
                ],
                'event_name' => 'item:completed',
            ],
        ]);

        Event::create([
            'data' => (object) [
                'event_name' => 'topir:migration',
                'date' => '2020-05-01',
                'completed' => 3,
                'overdue' => 0,
            ],
        ]);

        $this->assertEquals(4, Counts::where('date', '2020-05-01')->first()->completed_p4);
    }

    /** @test */
    public function overdueTasksAreImported()
    {
        Event::create([
            'data' => (object) [
                'event_name' => 'topir:migration',
                'date' => '2020-05-01',
                'completed' => 0,
                'overdue' => 6,
            ],
        ]);

        $this->assertEquals(6, Overdue::where('date', '2020-05-01')->first()->count);
    }
}
