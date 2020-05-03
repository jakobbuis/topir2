<?php

namespace Tests\Unit;

use App\Overdue;
use Carbon\Carbon;
use Tests\TestCase;

class OverdueTest extends TestCase
{
    /** @test */
    public function itKeepsALogOf30DaysPast()
    {
        Carbon::setTestNow('03-01-2020');

        Overdue::create(['date' => '2020-01-02', 'count' => 2]);
        Overdue::create(['date' => '2020-01-01', 'count' => 1]);

        $this->assertEquals(
            [
                0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                0, 0, 0, 0, 0, 0, 0, 1, 2, 0,
            ],
            Overdue::last30Days()
        );
    }
}
