<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Biometric;
use App\Job;
use App\Task;
use App\User;
use Auth;
use Carbon\Carbon;
use App\Clocktrack\Option;

class TimeclockController extends Controller
{
    public function __construc() {
        $this->middleware('auth');
    }
    
    public function calendar()
    {
        $biometrics = Auth::user()->biometric;
        return view('timeclock.index_calendar', [
            'biometrics' => $biometrics->sortByDesc('end_datetime'),
            'jobOptions' => Job::selectOptions(),
            'taskOptions' => Task::selectOptions(),
            'last_biometric' => Auth::user()->biometric->last(),
        ]);
    }

    public function timesheet(User $user)
    {
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        Carbon::setWeekEndsAt(Carbon::SATURDAY);

        $start = \Request::get('start');
        $end = \Request::get('end');

        $start = $start ? Carbon::parse($start) : Carbon::now();
        $end = $end ? Carbon::parse($end) : Carbon::now();

        
        $start = $start->startOfWeek();
        $end = $end->endOfWeek();

        $biometrics = Biometric::whereBetween('time_in', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
        $biometrics = $biometrics->where('user_id', $user->id)->get();
        $biometrics = $biometrics->map(function($biometric) use($user) {
            return (object)[
                'job' => $biometric->job,
                'task' => $biometric->task,
                'duration' => $biometric->duration_in_minutes,
                'date' => Carbon::parse($biometric->time_in)->format('D'),
            ];
        });
        
        return view('timeclock.index_timesheet',[
            'biometrics' => $biometrics,
            'week' => Option::daysIn($start, $end),
            'jobOptions' => Job::selectOptions(),
            'taskOptions' => Task::selectOptions(),
            'last_biometric' => Auth::user()->biometric ? Auth::user()->biometric->last() : [],
        ]);
    }

    public function store(Request $request) {
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
            ]
        );
        
        $employees = explode(",", $request->employees);
        collect($employees)->map(function ($employee) use ($request) {
            $timeInDate = Carbon::parse($request->start_date)->format(config('constant.dateFormat'));
            $timeInHours = Carbon::parse($request->start_time)->format(config('constant.timeFormat'));

            $timeOutDate = Carbon::parse($request->end_date)->format(config('constant.dateFormat'));
            $timeOutHours = Carbon::parse($request->end_time)->format(config('constant.timeFormat'));

            $biometric = new Biometric;
            $biometric->time_out = "{$timeOutDate} {$timeOutHours}";
            $biometric->time_in = "{$timeInDate} {$timeInHours}";
            $biometric->user_id = $employee;
            $biometric->job = $request->job;
            $biometric->task = $request->task;
            $biometric->notes = $request->notes;
            $biometric->file = $request->file;
            $biometric->active = isset($request->active) ? $request->active : 0;
            $biometric->lng = isset($request->lng) ? $request->lng : '';
            $biometric->lat = isset($request->lat) ? $request->lat : '';

            $biometric->save();
        });
        
        return back()->with('status', 'Added new biometric!');
            
    }


    public function update($biometric, Request $request) {
        $this->validate(
            $request, [
                'notes' => 'max:500',
                'end_date' => 'required',
                'end_time' => 'required',
            ]
        );

        $timeOutDate = Carbon::parse($request->end_date)->format(config('constant.dateFormat'));
        $timeOutHours = Carbon::parse($request->end_time)->format(config('constant.timeFormat'));

        $biometric = Biometric::find($biometric);
        $biometric->time_out = "{$timeOutDate} {$timeOutHours}";
        $biometric->notes = $request->notes;
        $biometric->active = 0;
        $biometric->file = $request->file;

        $biometric->save();

        return back()->with('updated', "{$biometric->user->fullname} biometric successfully updated!");
    }
}
