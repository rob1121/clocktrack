<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Biometric; 
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Clocktrack\Download\Interfaces\Downloadable;
use App\Clocktrack\Download\Timesheet;
use App\Clocktrack\Download\EmployeeSummary;

class ExcelDownloadController extends Controller {

    /**
     * construct controller
     */
    public function __construct() 
    {
        // $this->middleware('admin');
    }

    /**
     * download timesheet
     *
     * @param Request $request
     * @return void
     */
    public function timesheet(Request $request)
    {
        $this->download(new Timesheet($request));
    }

    public function employeeSummary(Request $request) 
    {
        $this->download(new EmployeeSummary($request));
    }

    /**
     * downloader
     *
     * @param Downloadable $downloader
     * @param Request $request
     * @return void
     */
    private function download(Downloadable $downloader) {
        try {
            $downloader->download();
        } catch (\Exception $e) {
            report($e);
        }
    }
}
