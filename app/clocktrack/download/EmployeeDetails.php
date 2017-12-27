<?php

namespace App\Clocktrack\Download;

use App\Clocktrack\Download\Interfaces\Downloadable;
use App\Clocktrack\Download\Extensions\ExcelExtract;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Biometric;

class EmployeeDetails extends ExcelExtract Implements Downloadable
{
    /**
     * date from
     *
     * @var string
     */
    protected $from;

    /**
     * date to
     *
     * @var string
     */
    protected $to;

    /**
     * construct
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        $today = Carbon::now();
        $this->request = $request;
        $this->from = clone $today;
        $this->to = clone $today;
    }

    /**
     * download timesheet
     * @return void
     */
    public function download() 
    {
        $timesheet = [];
        $this->fetchTimesheet($timesheets);
        $this->getRequiredColumns($timesheets);
        
        // dd($timesheets);
        $filename = $this->setFilename('employee details');
        $this->export($filename, $timesheets);
    }

    /**
     * fetch employees biometrics
     *
     * @return void
     */

    protected function fetchTimesheet(&$timesheets)
    {
        // date range
        $this->from = $this->request->has('from') ? Carbon::parse($this->request->from) : $this->from->startOfWeek();
        $this->to = $this->request->has('to') ? Carbon::parse($this->request->to) : $this->to->endOfWeek();

        //query between date range
        $biometrics = Biometric::whereBetween('time_in', [$this->from, $this->to]);
        
        //query where employee in employees
        if ($this->request->has('employees')) {
            $biometrics->whereIn('user_id', $this->request->employees);
        }

        $timesheets = $biometrics->get();
    }

    /**
     * select required columns only
     *
     * @param array $timesheet
     * @return void
     */
    protected function getRequiredColumns(&$timesheets)
    {
        $timesheets = $timesheets->map(function ($timesheet) {
            $minutesBreaktime = $timesheet->breaktime->isNotEmpty() ? $timesheet->breaktime->duration_in_minutes : 0;
            $total_minutes = $timesheet->duration_in_minutes + $minutesBreaktime;
            return [
                'first_name' => $timesheet->user->firstname,
                'last_name' => $timesheet->user->lastname,
                'job' => $timesheet->job,
                'task_code' => $timesheet->task_code ? : '-',
                'job_code' => $timesheet->job_code ? : '-',
                'task' => $timesheet->task,
                'time_in' => $timesheet->time_in,
                'time_out' => $timesheet->time_out,
                'minutes_breaktime' => $minutesBreaktime ? : '-',
                'minutes_billable' => $timesheet->duration_in_minutes ? : '-',
                'minutes_total' => $total_minutes ? : '-',
                'hours_minutes' => minutesToHourMinuteFormat($total_minutes),
            ];
        });
    }
}