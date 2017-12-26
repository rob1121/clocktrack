<?php

namespace App\Clocktrack\Download;

use App\Biometric;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Clocktrack\Download\Interfaces\Downloadable;

class Timesheet extends Extensions\Excel implements Downloadable
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
        //fetch from database
        $timesheets = $this->fetchTimesheet();

        //select required columns only
        $timesheets = $this->getRequiredColumns($timesheets);

        //group by employee
        $timesheets = $timesheets->groupBy('fullname');

        $timesheets = $this->arrangeOrderByJob($timesheets);

        $timesheets = $timesheets->map(function ($job, $employee) {
            return $this->excelDataValidFormat($job, $employee);
        });
        
        $filename = $this->setFilename('timesheet');
        $this->export($filename, $timesheets->flatten(1));
    }

    /**
     * select required columns only
     *
     * @param array $timesheet
     * @return void
     */
    protected function getRequiredColumns($timesheets)
    {
        return $timesheets->map(function ($timesheet) {
            return (object)[
                'fullname' => $timesheet->user->fullname,
                'job' => $timesheet->job,
                'task' => $timesheet->task,
                'time_in' => $timesheet->time_in,
                'start_date' => $timesheet->start_date,
                'duration_in_minutes' => $timesheet->duration_in_minutes,
            ];
        });
    }

    /**
     * fetch employees biometrics
     *
     * @return void
     */
    protected function fetchTimesheet()
    {
        // date range
        $this->from = $this->request->has('from') ? Carbon::parse($this->request->from) : $this->from->startOfWeek();
        $this->to = $this->request->has('to') ? Carbon::parse($this->request->to) : $this->to->endOfWeek();

        //query between date range
        $timesheets = Biometric::whereBetween('time_in', [$this->from, $this->to]);
        
        //query where employee in employees
        if ($this->request->has('employees')) {
            $timesheets->whereIn('user_id', $this->request->employees);
        }

        return $timesheets->get();
    }

    /**
     * group collection by job and fetch necessary data
     *
     * @param string $timesheets
     * @return void
     */
    protected function arrangeOrderByJob($timesheets)
    {
        $retVal = $timesheets->map(function ($timesheet) {
            $jobs = $timesheet->groupBy('job');
            $jobCollection = $this->getRequiredJobDetails($jobs);

            return $jobCollection;
        });

        return $retVal;
    }

    /**
     * get needed data from job collection
     *
     * @param array $jobs
     * @return void
     */
    protected function getRequiredJobDetails($jobs)
    {
        $retVal = $jobs->map(function ($job) {
            $retVal = (object)[
                'job' => $job->first()->job,
                'task' => $job->pluck('task')->unique()->implode(', '),
                'dates' => $job->groupBy('start_date'),
                'total' => $job->sum('duration_in_minutes'),
            ];

            return $retVal;
        });

        return $retVal;
    }

    /**
     * format collection into a valid excel data
     *
     * @param array $job
     * @param string $employee
     * @return void
     */
    protected function excelDataValidFormat($job, $employee)
    {
        $retVal = $job->map(function ($timesheet) use ($employee) {
            $retVal = [
                'fullname' => $employee,
                'job' => $timesheet->job,
                'task' => $timesheet->task,
            ];

            $dateWithValue = $this->fillDateRangeValue($timesheet);
            $retVal = array_merge($retVal, $dateWithValue);
            $retVal['total'] = minutesToHourMinuteFormat($timesheet->total);
            return $retVal;
        });

        return $retVal;
    }

    /**
     * fill dates with hours employee has worked on
     *
     * @param array $timesheet
     * @return void
     */
    protected function fillDateRangeValue($timesheet)
    {
        $retVal = [];
        $start = clone $this->from;
        while ($start->lessThanOrEqualTo($this->to)) {
            $currentDate = $start->format(config('constant.dateIndexFormat'));
            $retVal[$currentDate] = isset($timesheet->dates[$currentDate]) ? $timesheet->dates[$currentDate]->sum('duration_in_minutes') : 0;
            $retVal[$currentDate] = minutesToHourMinuteFormat($retVal[$currentDate]);
            $start = $start->addDay();
        }

        return $retVal;
    }
}
