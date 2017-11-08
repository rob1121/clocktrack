<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Task;
use App\User;
use App\AllowedUserForTask;

class TaskController extends Controller
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
        if(\Request::has('q')) {
            $q = \Request::get('q');
            $tasks = Task::where('title', 'LIKE', "%{$q}%")
            ->orWhere('code', 'LIKE', "%{$q}%")
            ->paginate(10);
        } else {
            $tasks = Task::paginate(10);
        }

        return view('task.index', ['tasks' => $tasks]);
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
        return view('task.create', ['employees' => $employees]);
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
        ]);

        $task = new Task;
        $task->title = $request->title;
        $task->code = $request->code;
        $task->save();

        

        //link employee
        $employees = [];
        $employeesIds = explode(',', $request->employees);
        if($request->taskAccessControlId === 'allow all') {
            $employees = User::all();
        } elseif($request->taskAccessControlId === 'allow only') {
            $employees = User::whereIn('id', $employeesIds)->get();
        } elseif($request->taskAccessControlId === 'allow any except') {
            $employees = User::whereNotIn('id', $employeesIds)->get();
        }
        
        $allowedEmployees = $employees->map(function($employee) use($task) {
            return [
                'user_id' => $employee->id,
                'task_id' => $task->id
            ];
        });

        AllowedUserForTask::insert($allowedEmployees->toArray());
        
        return redirect()->route('task.index')->with('status', 'Task Successfully added!!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $employees = User::all();
        $employees = $employees->map(function($employee) {
            return (object)[
                'value' => $employee->id,
                'text' => $employee->fullname
            ];
        });
        
        $selectedEmployees = $task->allowedUserForTask->map(function($task) {
            return $task->user_id;
        });

        return view('task.edit', [
            'employees' => $employees,
            'task' => $task,
            'selectedEmployees' => $selectedEmployees->implode(','),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $this->validate($request, [
            'title' => 'required|max:100',
        ]);
        $task->title = $request->title;
        $task->code = $request->code;
        $task->save();

        
        if($task->allowedUserForTask->isNotEmpty()) {
            $task->allowedUserForTask->each(function($user) {
                $user->delete();
            });
        }

        $employeesIds = explode(',', $request->employees);
        $allowedEmployees = collect($employeesIds)->map(function($employee) use($task) {
            return [
                'user_id' => $employee,
                'task_id' => $task->id
            ];
        });

        AllowedUserForTask::insert($allowedEmployees->toArray());
        
        return redirect()->route('task.index')->with('status', "Task  {$task->title} uccessfully updated!!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();
        
        return redirect()->route('task.index')->with('status', "Task {$task->title} Successfully updated!!");
    }

    public function isActive(Request $request, Task $task) {
        $task->active = $request->is_active;
        $task->save();
    }
}
