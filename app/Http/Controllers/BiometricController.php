<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Biometric;
use App\Schedule;
use App\Job;
use App\Task;
use App\BreakTime;
use App\Rules\Within24hrs;
use App\Rules\WithinDateTimeRange;
use App\Clocktrack\Option;

class BiometricController extends Controller
{
    public function __construct() {
        $this->middleware('admin');
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
            $date_out = Carbon::parse($request->end_date)->format(config('constant.dateFormat'));
            $time_out = Carbon::parse($request->end_time)->format(config('constant.timeFormat'));

            $date_in = Carbon::parse($request->start_date)->format(config('constant.dateFormat'));
            $time_in = Carbon::parse($request->start_time)->format(config('constant.timeFormat'));

            $path = '';
            if($request->has('file')) {
                $path = $requestfile->store('timeclock');
            }

            $biometric = new Biometric;
            $biometric->time_out = "{$date_out} {$time_out}";
            $biometric->time_in = "{$date_in} {$time_in}";
            $biometric->user_id = $employee;
            $biometric->job = $request->job;
            $biometric->job_code = Job::whereTitle($request->job)->first()->number;
            $biometric->task = $request->task;
            $biometric->task_code = Task::whereTitle($request->task)->first()->code;
            $biometric->notes = $request->notes;
            $biometric->file = $path;
            $biometric->active = isset($request->active) ? $request->active : 0;
            $biometric->lng = isset($request->lng) ? $request->lng : '';
            $biometric->lat = isset($request->lat) ? $request->lat : '';

            $biometric->save();

            collect($request->break_times)->map(function($bt) use($biometric) {
                $breakTime = breakTimeFormat(
                    $bt['break_in'], 
                    $bt['break_out'], 
                    Carbon::parse($bt['date_range']['date_from']),
                    Carbon::parse($bt['date_range']['date_to'])
                );

                $breaktimeDb = new BreakTime;
                $breaktimeDb->schedule_id = $biometric->id;
                $breaktimeDb->break_in = $breakTime->in;
                $breaktimeDb->break_out = $breakTime->out;

                $breaktimeDb->save();
            });
            
        });
        
        return redirect('/timesheet')->with('status', 'Added new schedule!');
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Biometric $biometric)
    {
        return view('schedules.edit', [
            'biometric' => $biometric,
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
    public function update(Request $request,Biometric $biometric)
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

        $date_in = Carbon::parse($request->start_date)->format(config('constant.dateFormat'));
        $time_in = Carbon::parse($request->start_time)->format(config('constant.timeFormat'));

        $date_out = Carbon::parse($request->end_date)->format(config('constant.dateFormat'));
        $time_out = Carbon::parse($request->end_time)->format(config('constant.timeFormat'));

        $biometric->user_id = $request->employee;
        $biometric->time_out = "{$date_out} {$time_out}";
        $biometric->time_in = "{$date_in} {$time_in}";
        $biometric->job = $request->job;
        $biometric->job_code = Job::whereTitle($request->job)->first()->number;
        $biometric->task = $request->task;
        $biometric->task_code = Task::whereTitle($request->task)->first()->code;
        $biometric->notes = $request->notes;
        $biometric->active = 0;
        $biometric->lng = isset($request->lng) ? $request->lng : '';
        $biometric->lat = isset($request->lat) ? $request->lat : '';
        $biometric->file = $request->file;

        $biometric->save();
        if ($biometric->breaktime->isNotEmpty()) {
            $biometric->breaktime->each(function($breaktime) {
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
    public function destroy(Biometric $biometric)
    {
        $biometric->delete();

        return back()->with('deleted', 'biometric successfully deleted');
    }
}
