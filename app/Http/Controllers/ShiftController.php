<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job;
use App\User;

class ShiftController extends Controller
{
    public function __construc() {
        $this->middleware('auth');
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

    public function storeBatch(Request $request) {
        dd($request->all());

        return [
            'message' => 'success',
            'success' => true,
        ];
    }
}
