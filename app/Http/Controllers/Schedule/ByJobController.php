<?php

namespace App\Http\Controllers\Schedule;

use App\Biometric;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ByJobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->middleware('admin');

        $from = Carbon::parse(\Request::get('date'))->startOfDay()->format(config('constant.dateTimeFormat'));
        $to = Carbon::parse(\Request::get('date'))->endOfDay()->format(config('constant.dateTimeFormat'));
        $schedules = Biometric::where('job', str_replace('-',' ', \Request::get('job')));
        $schedules->whereBetween('time_in', [$from, $to]);
        
        return view('schedules.by_job.index', ['schedules' => $schedules->get()]);
    }
}
