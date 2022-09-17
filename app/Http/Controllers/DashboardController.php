<?php

namespace App\Http\Controllers;

use App\Models\Counts;
use App\Models\Overdue;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'completed_p1' => Counts::last30DaysP1(),
            'completed_p2' => Counts::last30DaysP2(),
            'completed_p3' => Counts::last30DaysP3(),
            'completed_p4' => Counts::last30DaysP4(),
            'overdue' => Overdue::last30Days(),
        ]);
    }
}
