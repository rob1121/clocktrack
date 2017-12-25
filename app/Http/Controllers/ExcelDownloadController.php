<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Biometric; 
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ExcelDownloadController extends Controller {
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $from;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $to;

    /**
     * Undocumented function
     */
    public function __construct() 
    {
        // $this->middleware('admin');
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        $today = Carbon::now();
        $this->from = clone $today;
        $this->to = clone $today;
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function timesheet(Request $request) 
    {
        // date range
        $this->from = $request->has('from') ? Carbon::parse($request->from) : $this->from->startOfWeek();
        $this->to   = $request->has('to') ? Carbon::parse($request->to) : $this->to->endOfWeek();

        //query between date range
        $timesheets = Biometric::whereBetween('time_in', [$this->from, $this->to]);
        
        //query where employee in employees
        if($request->has('employees')) 
        {
            $timesheets->whereIn('user_id', $request->employees);
        }

        //fetch from database
        $timesheets = $timesheets->get();

        //select required columns only
        $timesheets = $timesheets->map(function($timesheet)
        {
            
           return $this->setItems($timesheet);
        });

        //group by employee
        $timesheets = $timesheets->groupBy('fullname');

        $timesheets = $timesheets->map(function($timesheet) 
        {
            $retVal['jobs'] = $timesheet->groupBy('job');
            $retVal['jobs']->map(function($job) use(&$retVal) {
                
                $retVal['dates'] = $job->groupBy('start_date');
                $retVal['dates']->map(function($date) use(&$retVal) {
                    //compute total time in minutes
                    $retVal['total'] = $date->sum('duration_in_minutes');
                    $retVal['job'] = $date->first()['job'];
                    $retVal['task'] = $date->first()['task'];
                });
            });
            
            return $retVal;
        });
        
        $timesheets = $timesheets->map(function($timesheet, $employee)
        {
            $retVal['fullname'] = $employee;
            $retVal['job'] = $timesheet['job'];
            $retVal['task'] = $timesheet['task'];
            $this->plotToDateRange($timesheet['dates'], $retVal);
            $retVal['total'] = minutesToHourMinuteFormat($timesheet['total']);

            return $retVal;
        });
        
        //export to excel
        Excel::create('timesheets', function($excel) use($timesheets) 
        {
           $this->setWorkBook($excel, $timesheets);
        })->export('xls');        
    }

    /**
     * Undocumented function
     *
     * @param [type] $excel
     * @param [type] $timesheets
     * @return void
     */
    protected function setWorkBook($excel, $timesheets) 
    {
        $excel->sheet('Sheet 1', function($sheet) use($timesheets) 
        {
            $this->setWorkSheet($sheet, $timesheets);
        });
    }

    /**
     * Undocumented function
     *
     * @param [type] $sheet
     * @param [type] $timesheets
     * @return void
     */
    protected function setWorkSheet($sheet, $timesheets) 
    {
        $sheet->fromArray($timesheets);
    }

    /**
     * Undocumented function
     *
     * @param [type] $timesheet
     * @param [type] $employee
     * @return void
     */
    protected function plotToDateRange($timesheet, &$collection)
    {
        while($this->from->lessThanOrEqualTo($this->to))
        {
            $currentDate = $this->from->format(config('constant.dateIndexFormat'));
            $collection[$currentDate] = isset($timesheet[$currentDate]) ? $timesheet[$currentDate]->sum('duration_in_minutes') : 0;
            $collection[$currentDate] = minutesToHourMinuteFormat($collection[$currentDate]);
            $this->from = $this->from->addDay();
        }    
        
        return $collection;
    }

    /**
     * select required columns only
     *
     * @param [type] $timesheet
     * @return void
     */
    protected function setItems($timesheet)
    {
        return [
            'fullname' => $timesheet->user->fullname,
            'job'  => $timesheet->job,
            'task'  => $timesheet->task,
            'time_in'  => $timesheet->time_in,
            'start_date'  => $timesheet->start_date,
            'duration_in_minutes' => $timesheet->duration_in_minutes,
        ];
    }
}
