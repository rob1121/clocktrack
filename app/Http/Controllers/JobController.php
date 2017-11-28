<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Job;
use App\User;
use App\Task;
use App\AllowedUserForJob;
use App\AllowedTaskForJob;
use Storage;

class JobController extends Controller
{
    const DONT_TRACK = 0;
    const NONE = 0;
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
        if(\Request::has('q')) {
            $q = \Request::get('q');
            $jobs = Job::where('title', 'LIKE', "%{$q}%");
            $jobs->orWhere('number', 'LIKE', "%{$q}%");
            $jobs = $jobs->paginate(10);
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
        $tasks = $tasks->map(function($task) {
            return (object)[
                'value' => $task->id,
                'text' => $task->title
            ];
        });

        return view('job.create', [
            'employees' => $employees,
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
            'description' => 'max:500',
            'file' => 'mimes:xls,xlsx,pdf,doc,docx,csv,jpeg,png,bmp,gif,svg',
            'color' => 'required',
            'total_hour_target' => [($request->track_labor_budget ? 'required' : ''), 'numeric'],
            'hours_remaining' => [($request->track_when_budget_hits ? 'required' : ''), 'numeric'],
        ]);
        
        $path = '';
        if($request->has('file')) {
            $path = $request->file->store('job');
        }

        $job = new Job;
        $job->title = $request->title;
        $job->number = $request->number;
        $job->description = $request->description;
        $job->file = $path;
        $job->color = $request->color;
        $job->track_labor_budget = $request->track_labor_budget ?:self::DONT_TRACK;
        $job->track_when_budget_hits = $request->track_when_budget_hits ?:self::DONT_TRACK;
        $job->hours_remaining = $request->hours_remaining ?:self::NONE;
        $job->total_hour_target = $request->total_hour_target ?:self::NONE;
        $job->address = $request->address;
        $job->city = $request->city;
        $job->state = $request->state;
        $job->postal_code = $request->postal_code;
        $job->country = $request->country;
        $job->remind_clockout = $request->remind_clockout ?: self::DONT_TRACK;
        $job->remind_clockin = $request->remind_clockin ?: self::DONT_TRACK;
        $job->save();

        //link employee
        $employees = [];
        $employeesIds = explode(',', $request->employees);
        if($request->employeeAccessControlId === 'allow all') {
            $employees = User::all();
        } elseif($request->employeeAccessControlId === 'allow only') {
            $employees = User::whereIn('id', $employeesIds)->get();
        } elseif($request->employeeAccessControlId === 'allow any except') {
            $employees = User::whereNotIn('id', $employeesIds)->get();
        }

        $allowedEmployees = $employees->map(function($employee) use($job) {
            return [
                'user_id' => $employee->id,
                'job_id' => $job->id
            ];
        });

        AllowedUserForJob::insert($allowedEmployees->toArray());

        //link employee
        $tasks = [];
        $tasksIds = explode(',', $request->employees);
        if($request->employeeAccessControlId === 'allow all') {
            $tasks = Task::all();
        } elseif($request->employeeAccessControlId === 'allow only') {
            $tasks = Task::whereIn('id', $tasksIds)->get();
        } elseif($request->employeeAccessControlId === 'allow any except') {
            $tasks = Task::whereNotIn('id', $tasksIds)->get();
        }

        $allowedTasks = $tasks->map(function($task) use($job) {
            return [
                'task_id' => $task->id,
                'job_id' => $job->id
            ];
        });

        AllowedTaskForJob::insert($allowedTasks->toArray());

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
        $tasks = $tasks->map(function($task) {
            return (object)[
                'value' => $task->id,
                'text' => $task->title
            ];
        });
        
        $selectedEmployees = $job->allowedUserForJob->map(function($job) {
            return $job->user_id;
        });
        
        $selectedTasks = $job->allowedTaskForJob->map(function($job) {
            return $job->task_id;
        });
        
        return view('job.edit', [
            'selectedEmployees' => $selectedEmployees->implode(','),
            'selectedTasks' => $selectedTasks->implode(','),
            'employees' => $employees,
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
            'description' => 'max:500',
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

        $path = '';
        if($request->has('file')) {
            Storage::delete($job->file);
            $path = $request->file->store('job');
        }
        
        $job->title = $request->title;
        $job->number = $request->number;
        $job->description = $request->description;
        $job->file = $path;
        $job->color = $request->color;
        $job->track_labor_budget = $request->track_labor_budget ?: self::DONT_TRACK;
        $job->track_when_budget_hits = $request->track_when_budget_hits ?: self::DONT_TRACK;
        $job->hours_remaining = $request->hours_remaining ?: self::NONE;
        $job->total_hour_target = $request->total_hour_target ?: self::NONE;
        $job->address = $request->address;
        $job->city = $request->city;
        $job->state = $request->state;
        $job->postal_code = $request->postal_code;
        $job->country = $request->country;
        $job->remind_clockout = $request->remind_clockout ?: self::DONT_TRACK;
        $job->remind_clockin = $request->remind_clockin ?: self::DONT_TRACK;
        $job->save();
        
        if($job->allowedUserForJob->isNotEmpty()) {
            $job->allowedUserForJob->each(function($user) {
                $user->delete();
            });
        }
        
        if($job->allowedTaskForJob->isNotEmpty()) {
            $job->allowedTaskForJob->each(function($task) {
                $task->delete();
            });
        }
        $allowedEmployees = explode(',', $request->employees);
        $allowedEmployees = collect($allowedEmployees)->filter();
        $allowedEmployees = $allowedEmployees->map(function($employee) use($job) {
            return [
                'user_id' => $employee,
                'job_id' => $job->id
            ];
        });
        
        AllowedUserForJob::insert($allowedEmployees->toArray());

        $allowedTasks = explode(',', $request->tasks);
        $allowedTasks = collect($allowedTasks)->filter();
        $allowedTasks = $allowedTasks->map(function($task) use($job) {
            return [
                'task_id' => $task,
                'job_id' => $job->id
            ];
        });

        AllowedTaskForJob::insert($allowedTasks->toArray());

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
        if($job->allowedUserForJob->isNotEmpty()) {
            $job->allowedUserForJob->each(function($user) {
                $user->delete();
            });
        }

        if($job->allowedTaskForJob->isNotEmpty()) {
            $job->allowedTaskForJob->each(function($task) {
                $task->delete();
            });
        }
        
        Storage::delete($job->file);
        $job->delete();
        
        return back()->with('status', 'Job Successfully deleted!!');
    }

    public function isActive(Request $request, Job $job) {
        $job->active = $request->is_active === 'true';
        $job->save();
    }
}
