<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job;
use App\Schedule;
use App\User;
use Carbon\Carbon;
use App\Clocktrack\Option;

class ShiftController extends Controller
{
    public function __construct() {
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = User::all();
        $employees = $employees->map(function($employee) {
            return [
                'id' => $employee->id,
                'title' => $employee->fullname,
            ];
        });
        return view('scheduler.index', [
            'jobs' => Job::all(),
            'employees' => $employees,
        ]);
    }

    public function store(Request $request) {
        $job = Job::find($request->job);
        $employee = User::find($request->employee);
        
        $schedule = new Schedule;
        $schedule->end_date = Carbon::parse($request->end)->format(config('constant.dateFormat'));
        $schedule->end_time = Carbon::parse($request->end)->format(config('constant.timeFormat'));
        $schedule->start_date = Carbon::parse($request->start)->format(config('constant.dateFormat'));
        $schedule->start_time = Carbon::parse($request->start)->format(config('constant.timeFormat'));
        $schedule->user_id = $employee->id;
        $schedule->job = $job->title;
        $schedule->color = $job->color;
        $schedule->notes = $request->notes;
        $schedule->save();
        
        return [
            'message' => 'success',
            'success' => true,
        ];
    }

    public function edit($shift) {
        $schedule = Schedule::find($shift);
        $employees = User::all();
        $employees = $employees->map(function($employee) {
            return (object)[
                'id' => $employee->id,
                'title' => $employee->fullname,
            ];
        });

        $jobs = Job::all()->map(function($job) {
            return (object)[
                'id' => $job->id,
                'title' => $job->title,
            ];
        });

        return view('scheduler.edit', [
            'schedule' => $schedule,
            'employees' => $employees,
            'jobs' => $jobs,
            'breaktimeOptions' => Option::breakTime(),
            ]);
    }

    public function update(Request $request, $shift) {
        $job = Job::find($request->job);
        $employee = User::find($request->employee);
        $schedule = Schedule::find($shift);
        
        $schedule->user_id = $employee->id;
        $schedule->job = $job->title;
        $schedule->color = $job->color;
        $schedule->notes = $request->notes;
        $schedule->start_date = dateFormat($request->start);
        $schedule->start_time = timeFormat($request->start);
        $schedule->end_date = dateFormat($request->end);
        $schedule->end_time = timeFormat($request->end);
        $schedule->save();

        // return redirect()->route('shift.index');
    }


    public function destroy($schedule) {
        $schedule = Schedule::find($schedule);
        $schedule->delete();
        
        return [
            'message' => 'success',
            'success' => true,
        ];
    }
}
