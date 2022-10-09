<?php

namespace Tests\Feature\Console\Commands;

use App\Models\Event;
use App\Models\Overdue;
use Carbon\Carbon;
use Tests\TestCase;

class CorrectOverdueTest extends TestCase
{
    /** @test */
    public function itCanIssueACorrection()
    {
        $this->artisan('overdue:correct 03-01-2020 3')
            ->assertSuccessful();

        $this->assertEquals(1, Event::count());
        $event = Event::first();
        $this->assertEquals('topir:overdue-correction', $event->data->event_name);
        $this->assertEquals('2020-01-03', $event->data->date);
        $this->assertEquals(3, $event->data->overdue);
    }

    /** @test */
    public function itTriggersRehydrationImmediatelyWhenACorrectionIsIssued()
    {
        Carbon::setTestNow('2020-01-04');

        $this->artisan('overdue:correct 03-01-2020 3')
            ->assertSuccessful();

        $projection = Overdue::last30Days();

        $this->assertEquals(0, array_pop($projection));
        $this->assertEquals(3, array_pop($projection));
        $this->assertEquals(0, array_pop($projection));
    }
}
