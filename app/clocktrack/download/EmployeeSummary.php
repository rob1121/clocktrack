<?php

namespace App\Clocktrack\Download;

use App\Biometric;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Clocktrack\Download\Interfaces\Downloadable;
use App\Clocktrack\Download\Extensions\ExcelExtract;

class EmployeeSummary extends ExcelExtract implements Downloadable
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
     * download employee summary
     * 
     * @return void
     */
    public function download()
    {
        $timesheets = [];

        //fetch from database
        $this->fetchTimesheet($timesheets);

        //select required columns only
        $this->getRequiredColumns($timesheets);

        //group by employee
        $this->groupByEmployee($timesheets);

        $timesheets = $timesheets->map(function ($timesheet) {
            return (object)[
                'dates' => $timesheet->groupBy('start_date'),
                'total' => $timesheet->sum('duration_in_minutes'),
            ];
        });

        $timesheets = $timesheets->map(function ($timesheet, $employee) {
            return $this->excelDataValidFormat($timesheet, $employee);
        });
        
        $filename = $this->setFilename('employee summary');
        $this->export($filename, $timesheets);
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
            return (object)[
                'fullname' => $timesheet->user->fullname,
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
     * format collection into a valid excel data
     *
     * @param array $timesheet
     * @param string $employee
     * @return void
     */
    protected function excelDataValidFormat($timesheet, $employee)
    {
        $retVal = [
            'fullname' => $employee,
        ];

        $dateWithValue = $this->fillDateRangeValue($timesheet);
        $retVal = array_merge($retVal, $dateWithValue);
        $retVal['total'] = minutesToHourMinuteFormat($timesheet->total);
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

    /**
     * group by employee
     *
     * @param array $timesheets
     * @return void
     */
    protected function groupByEmployee(&$timesheets)
    {
        $timesheets = $timesheets->groupBy('fullname');
    }
}
