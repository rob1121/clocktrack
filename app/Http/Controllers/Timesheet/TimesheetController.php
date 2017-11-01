<?php

namespace App\Http\Controllers\TimeSheet;

use App\User;
use App\Job;
use App\Task;
use App\Schedule;
use App\BreakTime;
use App\Rules\Within24hrs;
use App\Rules\WithinDateTimeRange;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;


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
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        Carbon::setWeekEndsAt(Carbon::SATURDAY);
        $startOfWeek = \Request::get('start_of_week');
        $endOfWeek = $startOfWeek ? Carbon::parse($startOfWeek)->endOfWeek() : Carbon::now()->endOfWeek();

        $startOfDate = $startOfWeek ? Carbon::parse($startOfWeek)->startOfWeek() : Carbon::now()->startOfWeek();
        $date = clone $startOfDate;
        $week = [];
        $week[] = new Carbon($date);
        
        while($date->diffInDays($endOfWeek)>0) {
            $date->addDay();
            $week[] = new Carbon($date);
        }
        
        $users = new User;
        $schedules = Schedule::fetchByDateRange($startOfDate, $endOfWeek);
        if(\Request::get('employee')) {
            $users = User::where('id', \Request::get('employee'));   
            $schedules = clone $users;
            $schedules = $schedules->with(['schedule' => function($query) use($startOfDate, $endOfWeek) {
                $query->whereBetween(
                    'start_date', 
                    [
                        $startOfDate->format(config('constant.dateFormat')), 
                        $endOfWeek->format(config('constant.dateFormat'))
                    ]
                );
            }])->first()->schedule;
        }

        $users = $users->with(['schedule' => function($query) use($startOfDate, $endOfWeek) {
            $query->whereBetween(
                'start_date', 
                [
                    $startOfDate->format(config('constant.dateFormat')), 
                    $endOfWeek->format(config('constant.dateFormat'))
                ]
            );
        }])->get();
        return view('timesheets.index', [
            'employeeOptions' => User::all(),
            'users' => $users,
            'schedules' => $schedules,
            'week' => (object)$week,
        ]);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breaktimeOptions = [];
        $date = Carbon::now()->startOfDay();
        $endOfDate = Carbon::now()->endOfDay();

        while ($endOfDate->diffInMinutes($date) > 0) {
            $breaktimeOptions[] =(object) [
                'value' => $date->format(config('constant.timeFormat')),
                'text' => $date->format('h:i a')
            ];
            $date->addMinutes(15);
        }

        return view('timesheets.create', [
            'employeeOptions' => User::all(),
            'jobOptions' => Job::selectOptions(),
            'taskOptions' => Task::selectOptions(),
            'breaktimeOptions' => $breaktimeOptions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dateRange = [
            "date_from" => trim("{$request->start_date} {$request->start_time}"),
            "date_to" => trim("{$request->end_date} {$request->end_time}"),
        ];
        
        $breakOuts = $request->break_out;
        $breakTimes = collect($request->break_in);

        $breakTimes = $breakTimes->map(function($breakIn, $index) use($breakOuts, $dateRange) {
            return [
                'date_range' => $dateRange,
                'break_in' => $breakIn,
                'break_out' => $breakOuts[$index],
            ];
        });

        $breakTimes = $breakTimes->filter(function($breakTime) {
            $isNullValue = !(is_null($breakTime['break_in']) && is_null($breakTime['break_out']));
            return $isNullValue;
        });

        $req = array_merge(
            $request->all(), ['date_range' => $dateRange], ['break_times' => $breakTimes->toArray()]
        );

        $request = new Request($req);
        
        $this->validate(
            $request, [
                'employees' => 'required',
                'job' => 'required',
                'task' => 'required',
                'notes' => 'max:500',
                'file' => 'mimes:pdf,doc,docx',
                'start_date' => 'required',
                'start_time' => 'required',
                'end_date' => 'required',
                'end_time' => 'required',
                'date_range.date_from'  => 'required',
                'date_range.date_to'  => 'required',
                'date_range'  => new Within24hrs,
                'break_times.*'  => new WithinDateTimeRange,
            ]
        );
        
        $employees = explode(",", $request->employees);
        collect($employees)->map(function ($employee) use ($request) {
            $schedule = new Schedule;
            $schedule->user_id = $employee;
            $schedule->start_date = Carbon::parse($request->start_date)->format(config('constant.dateFormat'));
            $schedule->start_time = Carbon::parse($request->start_time)->format(config('constant.timeFormat'));
            $schedule->end_date = Carbon::parse($request->end_date)->format(config('constant.dateFormat'));
            $schedule->end_time = Carbon::parse($request->end_time)->format(config('constant.timeFormat'));
            $schedule->job = $request->job;
            $schedule->task = $request->task;
            $schedule->notes = $request->notes;
            $schedule->file = $request->file;

            $schedule->save();

            collect($request->break_times)->map(function($bt) use($schedule) {
                $breakTime = breakTimeFormat(
                    $bt['break_in'], 
                    $bt['break_out'], 
                    Carbon::parse($bt['date_range']['date_from']),
                    Carbon::parse($bt['date_range']['date_to'])
                );

                $breaktimeDb = new BreakTime;
                $breaktimeDb->schedule_id = $schedule->id;
                $breaktimeDb->break_in = $breakTime->in;
                $breaktimeDb->break_out = $breakTime->out;

                $breaktimeDb->save();
            });
            
        });
        
        return redirect('/timesheet')->with('status', 'Added new schedule!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
