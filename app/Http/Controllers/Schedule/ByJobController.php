<?php

namespace App\Http\Controllers\Schedule;

use App\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ByJobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($job)
    {
        $schedules = Schedule::where('job', $job);
        $schedules->where('start_date', \Request::get('date'));
        
        return view('schedules.by_job.index', ['schedules' => $schedules->get()]);
    }
}
