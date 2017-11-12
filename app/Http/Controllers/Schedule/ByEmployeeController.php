<?php namespace App\Http\Controllers\Schedule;

use App\Rules\within24hrs;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Schedule;
use App\User;
use Carbon\Carbon;
class ByEmployeeController extends Controller
{
    public function __construct() {
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user, Request $request)
    {
        $this->middleware('admin');
        $user = $user->load(['biometric' => function($query) use($request) {
            $from = Carbon::parse($request->date)->startOfDay()->format(config('constant.dateTimeFormat'));
            $to = Carbon::parse($request->date)->endOfDay()->format(config('constant.dateTimeFormat'));
            $query->whereBetween('time_in', [$from, $to])->get();
        }]);
        
        return view('schedules.by_employee.index', ['user' => $user]);
    }
}
