<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Biometric;
use App\Job;
use App\Task;
use App\User;
use Auth;
use Carbon\Carbon;
use App\Clocktrack\Option;
use App\Notif;
use App\Schedule;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UnScheduledTimeReminder;

class TimeclockController extends Controller
{
    public function __construc() {
        $this->middleware('auth');
    }
    
    public function calendar()
    {
        $biometrics = Auth::user()->biometric->sortByDesc('time_out');
        $jobs = Auth::user()->allowedUserForJob;
        $jobs = $jobs->map(function($pivot) {
            return (object)[
                'value' => $pivot->job->title,
                'text'=>$pivot->job->title,
            ];
        });

        $tasks = Auth::user()->allowedUserForTask;
        $tasks = $tasks->map(function($pivot) { 
            return (object)[
                'value' => $pivot->task->title,
                'text'=>$pivot->task->title,
            ];
        });

        return view('timeclock.index_calendar', [
            'biometrics' => $biometrics,
            'jobOptions' => $jobs,
            'taskOptions' => $tasks,
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
        $jobs = Auth::user()->allowedUserForJob;
        $jobs = $jobs->map(function($pivot) {
            return (object)[
                'value' => $pivot->job->title,
                'text'=>$pivot->job->title,
            ];
        });

        $tasks = Auth::user()->allowedUserForTask;
        $tasks = $tasks->map(function($pivot) { 
            return (object)[
                'value' => $pivot->task->title,
                'text'=>$pivot->task->title,
            ];
        });
        
        return view('timeclock.index_timesheet',[
            'biometrics' => $biometrics,
            'week' => Option::daysIn($start, $end),
            'jobOptions' => $jobs,
            'taskOptions' => $tasks,
            'last_biometric' => Auth::user()->biometric ? Auth::user()->biometric->last() : [],
        ]);
    }

    public function store(Request $request) {
        
        $notif = Notif::first();

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
                'file' => 'mimes:xls,xlsx,pdf,doc,docx,csv,jpeg,png,bmp,gif,svg',
            ]
        );
        
        $path = '';
        if($request->has('file')) {
            $path = $request->file->store('timeclock');
        }

        $employees = explode(",", $request->employees);
        collect($employees)->map(function ($employee) use ($request, $path) {
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
            $biometric->file = $path;
            $biometric->active = isset($request->active) ? $request->active : 0;
            $biometric->lng = isset($request->lng) ? $request->lng : '';
            $biometric->lat = isset($request->lat) ? $request->lat : '';

            $biometric->save();

            if($notif->unscheduled_time)
            {
                $schedule = Schedule::whereUserId($employee);
                $schedule = $schedule->whereStartDate(Carbon::now()->toDateString());
                $schedule = $schedule->get();
                if($schedule->isEmpty())
                {
                    $user = User::find($employee);
                    Notification::send($user, new UnScheduledTimeReminder());
                }
            }
        });
        
        return back()->with('status', 'Added new biometric!');
            
    }


    public function update($biometric, Request $request) {
        $this->validate(
            $request, [
                'notes' => 'max:500',
                'end_date' => 'required',
                'end_time' => 'required',
                'file' => 'mimes:xls,xlsx,pdf,doc,docx,csv,jpeg,png,bmp,gif,svg',
            ]
        );
        
        $biometric = Biometric::find($biometric);
        $path = '';
        if($request->has('file')) {
            Storage::delete($biometric->file);
            $path = $request->file->store('timeclock');
        }

        $timeOutDate = Carbon::parse($request->end_date)->format(config('constant.dateFormat'));
        $timeOutHours = Carbon::parse($request->end_time)->format(config('constant.timeFormat'));
        
        $biometric->time_out = "{$timeOutDate} {$timeOutHours}";
        $biometric->notes = $request->notes;
        $biometric->active = 0;
        $biometric->file = $path;

        $biometric->save();

        return back()->with('updated', "{$biometric->user->fullname} biometric successfully updated!");
    }
}
