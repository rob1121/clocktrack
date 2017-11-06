<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Biometric; 
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('dashboard.index', [
            'active' => Biometric::active(),
        ]);
    }

    public function forceClockout(Biometric $biometric) {

        //TODO: notify user via email
        $biometric->end_date = Carbon::now()->format(config('constant.dateFormat'));
        $biometric->end_time = Carbon::now()->format(config('constant.timeFormat'));
        $biometric->active = 0;

        $biometric->save();

        return back();
    }
}
