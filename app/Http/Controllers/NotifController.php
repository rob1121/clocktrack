<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notif;
use App\User;
use Carbon\Carbon;
use App\Clocktrack\Option;

const TIME_FORMAT = 'h:i a';

class NotifController extends Controller
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
            return (object)[
                'value' => $employee->id,
                'text' => $employee->fullname
            ];
        });

        return view('notifications.index', [
            'notif' => Notif::first(),
            'times' => Option::breakTime(),
            'employees' => $employees,
        ]);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Notif $notif)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notif $notification)
    {
        $this->validate($request, [
            'clock_in' => 'required',
            'clock_out' => 'required',
            'schedule_clock_in' => 'required',
            'schedule_clock_out' => 'required',
        ]);
        
        $notification->clock_in = $request->clock_in;
        $notification->clock_out = $request->clock_out;
        $notification->monday = $request->monday?:0;
        $notification->tuesday = $request->tuesday?:0;
        $notification->wednesday = $request->wednesday?:0;
        $notification->thursday = $request->thursday?:0;
        $notification->friday = $request->friday?:0;
        $notification->saturday = $request->saturday?:0;
        $notification->sunday = $request->sunday?:0;
        $notification->exclude_admin = $request->exclude_admin?:0;
        $notification->schedule_clock_in = $request->schedule_clock_in;
        $notification->schedule_clock_out = $request->schedule_clock_out;
        $notification->recipient = $request->recipient;
        $notification->early_in = $request->early_in?:0;
        $notification->early_out = $request->early_out?:0;
        $notification->late_in = $request->late_in?:0;
        $notification->late_out = $request->late_out?:0;
        $notification->missing_in = $request->missing_in?:0;
        $notification->missing_out = $request->missing_out?:0;
        $notification->unscheduled_time = $request->unscheduled_time?:0;
        $notification->location_tampering = $request->location_tampering?:0;
        $notification->send_notification = $request->send_notification?:0;

        $notification->save();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
