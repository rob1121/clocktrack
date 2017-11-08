<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Task;

class TaskController extends Controller
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
            $tasks = Task::where('title', 'LIKE', "%{$q}%")
            ->orWhere('number', 'LIKE', "%{$q}%")
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
        return view('task.create', ['employees' => $tasks]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate
        //store task
        //link employee
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
        
        return view('task.edit', [
            'employees' => $tasks,
            'task' => $task
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
        return redirect()->route('task.index')->with('status', "Task {$task->title} Successfully updated!!");
    }

    public function isActive(Request $request, Task $task) {
        $task->active = $request->is_active;
        $task->save();
    }
}
