<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job;
use App\Schedule;
use App\User;
use Carbon\Carbon;

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
        $schedule->notes = $request->notes;
        $schedule->save();
        
        return [
            'message' => 'success',
            'success' => true,
        ];
    }

    public function update(Request $request,$shift) {
        $job = Job::find($request->job);
        $employee = User::find($request->employee);
        $schedule = Schedule::find($shift);

        $schedule->user_id = $employee->id;
        $schedule->job = $job->title;
        $schedule->start_date = Carbon::parse($request->start)->format('Y-m-d');
        $schedule->start_time = Carbon::parse($request->start)->format('H:i:s');
        $schedule->end_date = Carbon::parse($request->end)->format('Y-m-d');
        $schedule->end_time = Carbon::parse($request->end)->format('H:i:s');
        $schedule->save();

        return [
            'success' => true,
            'message' => 'Successfully updated'
        ];
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
