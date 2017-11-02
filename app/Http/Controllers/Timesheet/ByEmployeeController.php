<?php namespace App\Http\Controllers\TimeSheet;

use App\Rules\within24hrs;
use App\Schedule;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ByEmployeeController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user, Request $request)
    {
        $user = $user->load(['schedule' => function($query) use($request) {
            $query->where('start_date', $request->date)->get();
        }]);

        return view('timesheets.by_employee.index', ['user' => $user]);
    }
}
