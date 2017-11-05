<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Schedule;
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
        $schedules = Auth::user()->schedule;
        return view('timeclock.index_calendar', [
            'schedules' => $schedules->sortByDesc('end_datetime'),
            'jobOptions' => Job::selectOptions(),
            'taskOptions' => Task::selectOptions(),
            'last_schedule' => Auth::user()->schedule->last(),
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

        $schedules = Schedule::whereBetween('start_date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
        $schedules = $schedules->where('user_id', $user->id)->get();

        $schedules = $schedules->map(function($sched) use($user) {
            return (object)[
                'job' => $sched->job,
                'task' => $sched->task,
                'duration' => $sched->duration_in_minutes,
                'date' => Carbon::parse($sched->start_date)->format('D'),
            ];
        });
        
        return view('timeclock.index_timesheet',[
            'schedules' => $schedules,
            'week' => Option::daysIn($start, $end),
            'jobOptions' => Job::selectOptions(),
            'taskOptions' => Task::selectOptions(),
            'last_schedule' => Auth::user()->schedule->last(),
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
        });
        
        return back()->with('status', 'Added new schedule!');
            
    }


    public function update($schedule, Request $request) {
        $this->validate(
            $request, [
                'notes' => 'max:500',
                'end_date' => 'required',
                'end_time' => 'required',
            ]
        );

        $schedule = Schedule::find($schedule);
        $schedule->end_date = Carbon::parse($request->end_date)->format(config('constant.dateFormat'));
        $schedule->end_time = Carbon::parse($request->end_time)->format(config('constant.timeFormat'));
        $schedule->notes = $request->notes;
        $schedule->active = 0;
        $schedule->file = $request->file;

        $schedule->save();

        return back()->with('updated', "{$schedule->user->fullname} schedule successfully updated!");
    }
}
