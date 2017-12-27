<?php 

namespace App\Clocktrack\Download;

use App\Clocktrack\Download\Extensions\ExcelExtract;
use App\Clocktrack\Download\Interfaces\Downloadable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Job;
use App\Biometric;

class JobDetails extends ExcelExtract implements Downloadable {
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

        // date range
        $this->from = $this->from->startOfWeek();
        $this->to = $this->to->endOfWeek();
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
        
        dd($timesheets);

        $from = clone $this->from;
        $from = $from->format(cpnfig('constant.dateIndexFormat'));

        $to = clone $this->to;
        $to = $to->format(cpnfig('constant.dateIndexFormat'));

        $data = [
            ["Job Details Report"],
            ["{$from} - {$to}"],
            [],
        ];


        $filename = $this->setFilename('employee details');
        $this->hasCustomHeader(true);
        $this->export($filename, $data);
    }

    /**
     * select required columns only
     *
     * @param array $timesheet
     * @return void
     */
    protected function getRequiredColumns(&$timesheets)
    {

        $timesheets = $timesheets->groupBy('job');
        $timesheets = $timesheets->map(function ($job) {
            $retVal = [
                [],
                [$job->first()->job],
                ['Date', 'Employee', 'In', 'Out', 'Task', 'Break', 'Total'],
            ];

            $grandTotal = 0;
            $job->map(function($timesheet) use(&$retVal, &$grandTotal) {
                $minutesBreaktime = $timesheet->breaktime->isNotEmpty() ? $timesheet->breaktime->duration_in_minutes : 0;
                $total_minutes = $timesheet->duration_in_minutes + $minutesBreaktime;
                $grandTotal += $total_minutes;

                array_push($retVal, [
                    Carbon::parse($timesheet->time_in)->format(config('constant.dateIndexFormat')),
                    $timesheet->user->fullname,
                    $timesheet->time_in,
                    $timesheet->time_out,
                    $timesheet->task,
                    $minutesBreaktime ? : '-',
                    minutesToHourMinuteFormat($total_minutes),
                ]);
            });

            $grandPerEmployeeTotal = 0;
            $perEmployee = $job->groupBy('fullname');
            $totalEmployee = $perEmployee->map(function($timesheet) use(&$grandPerEmployeeTotal) {
                $minutesBreaktime = $timesheet->breaktime->isNotEmpty() ? $timesheet->breaktime->duration_in_minutes : 0;
                $total_minutes = $timesheet->duration_in_minutes + $minutesBreaktime;
                $grandPerEmployeeTotal += $total_minutes;

                return [$timesheet->user->fullname, $total_minutes];
            });

            $grandPerTaskTotal = 0;
            $perTask = $job->groupBy('fullname');
            $totalTask = $perTask->map(function($timesheet) use(&$grandPerTaskTotal) {
                $minutesBreaktime = $timesheet->breaktime->isNotEmpty() ? $timesheet->breaktime->duration_in_minutes : 0;
                $total_minutes = $timesheet->duration_in_minutes + $minutesBreaktime;
                $grandPerTaskTotal += $total_minutes;

                return [$timesheet->task, $total_minutes];
            });

            $retVal[] = ['', '', '', '', '', 'Total', minutesToHourMinuteFormat($grandTotal)];
            $retVal[] = [];
            $retVal[] = ['Employee Total'];
            $retVal[] = $totalEmployee;
            $retVal[] = ['Total', minutesToHourMinuteFormat($grandPerEmployeeTotal)];
            $retVal[] = [];
            $retVal[] = ['Task Total'];
            $retVal[] = $perTask;
            $retVal[] = ['Total', minutesToHourMinuteFormat($grandPerTaskTotal)];
            $retVal[] = [];
            
            return $retVal;
        });
    }

    /**
     * fetch employees biometrics
     *
     * @return void
     */
    protected function fetchTimesheet(&$timesheets)
    {
        if($this->request->has('from')) $this->from = Carbon::parse($this->request->from);
        if($this->request->has('to')) $this->to = Carbon::parse($this->request->to);

        //query between date range
        $biometrics = Biometric::whereBetween('time_in', [$this->from, $this->to]);
        
        //query where employee in employees
        if ($this->request->has('employees')) {
            $biometrics->whereIn('user_id', $this->request->employees);
        }

        $timesheets = $biometrics->get();
    }
}