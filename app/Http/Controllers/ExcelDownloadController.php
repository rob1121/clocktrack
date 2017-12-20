<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User; 
use Maatwebsite\Excel\Facades\Excel;

class ExcelDownloadController extends Controller
{
    public function __construct() {
        // $this->middleware('admin');
    }

    public function timesheet(Request $request) {
        $users = new User;
        $users = $users->with('biometric');
        if($request->has('employees')) {
            $users = $users->whereIn('id', $request->employees);
        }

        $users = $users->get();

        Excel::create('users', function($excel) use($users) {
            $excel->sheet('Sheet 1', function($sheet) use($users) {
                $sheet->fromArray($users->biometrics);
            });
        })->export('xls');        
    }
}
