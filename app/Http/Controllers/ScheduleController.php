<?php namespace App\Http\Controllers;

use App\Rules\within24hrs;
use App\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $req = array_merge($request->all(), [
            'date_range' => [
                "date_from" => trim("{$request->start_date} {$request->start_time}"),
                "date_to" => trim("{$request->end_date} {$request->end_time}"),
        ]]);

        $request = new Request($req);
        
		$this->validate(
			$request, [
                'employees' => 'required',
                'job' => 'required',
                'task' => 'required',
                'notes' => 'max:500',
                'file' => 'mimes:pdf,doc,docx',
				'date_range.date_from'  => 'required',
				'date_range.date_to'  => 'required',
				'date_range'  => new within24hrs,
			]
        );
        

        $employees = explode(",", $request->employees);
        collect($employees)->map(function ($employee) use ($request) {
            $schedule = new Schedule;
            $schedule->user_id = $employee;
            $schedule->start_date = $request->start_date;
            $schedule->start_time = $request->start_time;
            $schedule->end_date = $request->end_date;
            $schedule->end_time = $request->end_time;
            $schedule->job = $request->job;
            $schedule->task = $request->task;
            $schedule->notes = $request->notes;
            $schedule->file = $request->file;

            $schedule->save();
        });
        
        return back()->with('status', 'Added new schedule!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
