<?php

namespace App\Http\Controllers\TimeSheet;

use App\User;
use App\Job;
use App\Task;
use App\Schedule;
use App\BreakTime;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Clocktrack\Option;

class TimesheetController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $startOfWeek = \Request::get('start_of_week');
        $endOfWeek = $startOfWeek ? Carbon::parse($startOfWeek)->endOfWeek() : Carbon::now()->endOfWeek();
        $startOfDate = $startOfWeek ? Carbon::parse($startOfWeek)->startOfWeek() : Carbon::now()->startOfWeek();
        $date = clone $startOfDate;

        $config = [    
            $startOfDate->format(config('constant.dateFormat')),
            $endOfWeek->format(config('constant.dateFormat')),
            \Request::get('employee')
        ];
        
        return view('timesheets.index', [
            'employeeOptions' => User::all(),
            'users' => Option::employeesWithSchedule(...$config),
            'schedules' => Option::schedules(...$config),
            'week' => Option::daysIn($startOfDate, $endOfWeek),
        ]);
    }

    public function whosWorking() {
        
    }
}
