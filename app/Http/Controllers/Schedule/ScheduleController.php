<?php

namespace App\Http\Controllers\Schedule;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Schedule;
use App\Job;
use App\Task;
use App\BreakTime;
use App\Rules\Within24hrs;
use App\Rules\WithinDateTimeRange;
use App\Clocktrack\Option;

class ScheduleController extends Controller
{
    public function __construc() {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('schedules.create', [
            'employeeOptions' => Option::employees(),
            'jobOptions' => Job::selectOptions(),
            'taskOptions' => Task::selectOptions(),
            'breaktimeOptions' => Option::breakTime(),
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
            $schedule->end_date = Carbon::parse($request->end_date)->format(config('constant.dateFormat'));
            $schedule->end_time = Carbon::parse($request->end_time)->format(config('constant.timeFormat'));
            $schedule->start_date = Carbon::parse($request->start_date)->format(config('constant.dateFormat'));
            $schedule->start_time = Carbon::parse($request->start_time)->format(config('constant.timeFormat'));
            $schedule->user_id = $employee;
            $schedule->job = $request->job;
            $schedule->task = $request->task;
            $schedule->notes = $request->notes;
            $schedule->file = $request->file;
            $schedule->active = isset($request->active) ? $request->active : 0;
            $schedule->lng = isset($request->lng) ? $request->lng : '';
            $schedule->lat = isset($request->lat) ? $request->lat : '';

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
    public function edit(Schedule $schedule)
    {
        return view('schedules.edit', [
            'schedule' => $schedule,
            'employeeOptions' => Option::employees(),
            'breaktimeOptions' => Option::breakTime(),
            'jobOptions' => Job::selectOptions(),
            'taskOptions' => Task::selectOptions(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Schedule $schedule)
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
                'employee' => 'required',
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


        $schedule->user_id = $request->employee;
        $schedule->end_date = Carbon::parse($request->end_date)->format(config('constant.dateFormat'));
        $schedule->end_time = Carbon::parse($request->end_time)->format(config('constant.timeFormat'));
        $schedule->job = $request->job;
        $schedule->task = $request->task;
        $schedule->notes = $request->notes;
        $schedule->active = 0;
        $schedule->lng = isset($request->lng) ? $request->lng : '';
        $schedule->lat = isset($request->lat) ? $request->lat : '';
        $schedule->file = $request->file;

        $schedule->save();
        if ($schedule->breaktime->isNotEmpty()) {
            $schedule->breaktime->each(function($breaktime) {
                $breaktime->delete();
            });
        }
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

        return redirect()
            ->route('schedule.by_employee', ['user' => $schedule->user_id,'date' => $schedule->start_date])
            ->with('updated', "{$schedule->user->fullname} schedule successfully updated!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return back()->with('deleted', 'schedule successfully deleted');
    }
}
