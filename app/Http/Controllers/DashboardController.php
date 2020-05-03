<?php

namespace App\Http\Controllers;

use App\Counts;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'completed' => Counts::last30Days(),
        ]);
    }
}
