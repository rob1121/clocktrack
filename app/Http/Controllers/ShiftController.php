<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job;
use App\Schedule;
use App\User;

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
        return [
            'request' => $request->all(),
            'job' => Job::find($request->job),
            'employee' => User::find($request->employee)
        ];

        $schedule = new Schedule;
        $schedule->end_date = Carbon::parse($request->end_date)->format(config('constant.dateFormat'));
        $schedule->end_time = Carbon::parse($request->end_time)->format(config('constant.timeFormat'));
        $schedule->start_date = Carbon::parse($request->start_date)->format(config('constant.dateFormat'));
        $schedule->start_time = Carbon::parse($request->start_time)->format(config('constant.timeFormat'));
        $schedule->user_id = $request->$employee;
        $schedule->job = $request->job;
        $schedule->task = $request->task;

        $schedule->save();


        return [
            'message' => 'success',
            'success' => true,
        ];
    }

    public function update(Request $request, Schedule $schedule) {
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
}
