<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Schedule; 
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('dashboard.index', [
            'active' => Schedule::active(),
        ]);
    }

    public function forceClockout(Schedule $schedule) {

        //TODO: notify user via email
        $schedule->end_date = Carbon::now()->format(config('constant.dateFormat'));
        $schedule->end_time = Carbon::now()->format(config('constant.timeFormat'));
        $schedule->active = 0;

        $schedule->save();

        return back();
    }
}
