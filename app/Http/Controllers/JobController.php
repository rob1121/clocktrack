<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Job;
use App\User;
use App\Task;
use App\AllowedUserForJob;
use App\AllowedTaskForJob;

class JobController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Request::has('q')) {
            $q = \Request::get('q');
            $jobs = Job::where('title', 'LIKE', "%{$q}%")
            ->orWhere('number', 'LIKE', "%{$q}%")
            ->paginate(10);
        } else {
            $jobs = Job::paginate(10);
        }

        return view('job.index', ['jobs' => $jobs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = User::all();
        $employees = $employees->map(function($employee) {
            return (object)[
                'value' => $employee->id,
                'text' => $employee->fullname
            ];
        });

        $tasks = Task::all();
        $tasks = $tasks->map(function($employee) {
            return (object)[
                'value' => $employee->id,
                'text' => $employee->title
            ];
        });

        return view('job.create', [
            'employees' => $tasks,
            'tasks' => $tasks
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            
        $this->validate($request, [
            'title' => 'required|max:100',
            'number' => 'required|numeric',
            'description' => 'required|max:500',
            'file' => 'mimes:xls,xlsx,pdf,doc,docx,csv,jpeg,png,bmp,gif,svg',
            'color' => 'required',
            'employees.*' => 'required|numeric',
            'tasks.*' => 'required|numeric',
            'total_hour_target' => 'required|numeric',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required|numeric',
            'country' => 'required',
        ]);

        $job = new Job;
        $job->title = $request->title;
        $job->number = $request->number;
        $job->description = $request->description;
        $job->file = $request->file;
        $job->color = $request->color;
        $job->total_hour_target = $request->total_hour_target;
        $job->address = $request->address;
        $job->city = $request->city;
        $job->state = $request->state;
        $job->postal_code = $request->postal_code;
        $job->country = $request->country;

        //link employee
        collect($request->employees)->map(function($employee) use($job) {
            $allowedUserForJob = new AllowedUserForJob;
            $allowedUserForJob->user_id = $employee;
            $allowedUserForJob->job_id = $job->id;
            $allowedUserForJob->save();
        });

        //link task
        collect($request->tasks)->map(function($task) use($job) {
            $allowedTaskForJob = new AllowedTaskForJob;
            $allowedTaskForJob->task_id = $task;
            $allowedTaskForJob->job_id = $job->id;
            $allowedTaskForJob->save();
        });

        return redirect()->route('job.index')->with('status', 'Job Successfully added!!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Job $job)
    {
        $employees = User::all();
        $employees = $employees->map(function($employee) {
            return (object)[
                'value' => $employee->id,
                'text' => $employee->fullname
            ];
        });

        $tasks = Task::all();
        $tasks = $tasks->map(function($employee) {
            return (object)[
                'value' => $employee->id,
                'text' => $employee->title
            ];
        });

        return view('job.edit', [
            'employees' => $tasks,
            'tasks' => $tasks,
            'job' => $job
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Job $job)
    {
            
        $this->validate($request, [
            'title' => 'required|max:100',
            'number' => 'required|numeric',
            'description' => 'required|max:500',
            'file' => 'mimes:xls,xlsx,pdf,doc,docx,csv,jpeg,png,bmp,gif,svg',
            'color' => 'required',
            'employees.*' => 'required|numeric',
            'tasks.*' => 'required|numeric',
            'total_hour_target' => 'required|numeric',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required|numeric',
            'country' => 'required',
        ]);

        $job = new Job;
        $job->title = $request->title;
        $job->number = $request->number;
        $job->description = $request->description;
        $job->file = $request->file;
        $job->color = $request->color;
        $job->total_hour_target = $request->total_hour_target;
        $job->address = $request->address;
        $job->city = $request->city;
        $job->state = $request->state;
        $job->postal_code = $request->postal_code;
        $job->country = $request->country;

        $job->allowedUserForJob->each(function($user) {
            $user->delete();
        });

        $job->allowedTaskForJob->each(function($task) {
            $task->delete();
        });

        //link employee
        collect($request->employees)->map(function($employee) use($job) {
            $allowedUserForJob = new AllowedUserForJob;
            $allowedUserForJob->user_id = $employee;
            $allowedUserForJob->job_id = $job->id;
            $allowedUserForJob->save();
        });

        //link task
        collect($request->tasks)->map(function($task) use($job) {
            $allowedTaskForJob = new AllowedTaskForJob;
            $allowedTaskForJob->task_id = $task;
            $allowedTaskForJob->job_id = $job->id;
            $allowedTaskForJob->save();
        });
        return redirect()->route('job.index')->with('status', 'Job Successfully updated!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {

        $job->allowedUserForJob->each(function($user) {
            $user->delete();
        });

        $job->allowedTaskForJob->each(function($task) {
            $task->delete();
        });
        $job->delete();
        
        return redirect()->route('job.index')->with('status', 'Job Successfully deleted!!');
    }

    public function isActive(Request $request, Job $job) {
        $job->active = $request->is_active;
        $job->save();
    }
}
