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
use App\Clocktrack\Download\EmployeeDetails;
use App\Clocktrack\Download\JobDetails;
use App\Clocktrack\Download\TaskDetails;
use App\Clocktrack\Download\JobSummary;
use App\Clocktrack\Download\TaskSummary;

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

    /**
     * download job summary
     *
     * @param Request $request
     * @return void
     */
    public function jobSummary(Request $request)
    {
        $this->download(new JobSummary($request));
    }

    /**
     * download task summary
     *
     * @param Request $request
     * @return void
     */
    public function taskSummary(Request $request)
    {
        $this->download(new TaskSummary($request));
    }

    /**
     * download employee summary
     *
     * @param Request $request
     * @return void
     */
    public function employeeSummary(Request $request)
    {
        $this->download(new EmployeeSummary($request));
    }

    /**
     * download employee details
     *
     * @param Request $request
     * @return void
     */
    public function employeeDetails(Request $request)
    {
        $this->download(new EmployeeDetails($request));
    }

    /**
     * download job details
     *
     * @param Request $request
     * @return void
     */
    public function jobDetails(Request $request)
    {
        $this->download(new JobDetails($request));
    }

    /**
     * download task details
     *
     * @param Request $request
     * @return void
     */
    public function taskDetails(Request $request)
    {
        $this->download(new TaskDetails($request));
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
