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
        $employee_id = \Request::get('employee');
        $config = [    
            $startOfDate->format(config('constant.dateTimeFormat')),
            $endOfWeek->format(config('constant.dateTimeFormat')),
            $employee_id
        ];
        
        $employees = $employee_id ? User::whereId($employee_id) : new User;
        $employees = $employees->with(['biometric' => function($query) use($config) {
            $query->whereBetween('time_in', [$config[0], $config[1]]);
        }]);
        
        return view('timesheets.index', [
            'employees' => $employees->get(),
            'biometrics' => Option::biometrics(...$config),
            'week' => Option::daysIn($startOfDate, $endOfWeek),
        ]);
    }
}
