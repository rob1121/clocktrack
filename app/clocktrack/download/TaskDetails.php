<?php 

namespace App\Clocktrack\Download;

use App\Clocktrack\Download\Extensions\ExcelExtract;
use App\Clocktrack\Download\Interfaces\Downloadable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Job;
use App\Biometric;

class TaskDetails extends ExcelExtract implements Downloadable
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

        $from = clone $this->from;
        $from = $from->format(config('constant.dateIndexFormat'));

        $to = clone $this->to;
        $to = $to->format(config('constant.dateIndexFormat'));

        $data = [
            ["Task Details Report"],
            ["{$from} - {$to}"],
            []
        ];

        $data = array_merge($data, $timesheets->flatten(1)->values()->toArray());

        $filename = $this->setFilename('task details');
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
        $timesheets = $timesheets->groupBy('task');
        $timesheets = $timesheets->map(function ($task) {
            $retVal = [];

            $retVal[] = [$task->first()->task];
            $retVal[] = ['Date', 'Employee', 'In', 'Out', 'Job', 'Break', 'Total'];

            $grandTotal = 0;
            $task->map(function ($timesheet) use (&$retVal, &$grandTotal) {
                $minutesBreaktime = $timesheet->breaktime->isNotEmpty() ? $timesheet->breaktime->duration_in_minutes : 0;
                $total_minutes = $timesheet->duration_in_minutes + $minutesBreaktime;
                $grandTotal += $total_minutes;

                array_push($retVal, [
                    Carbon::parse($timesheet->time_in)->format(config('constant.dateIndexFormat')),
                    $timesheet->user->fullname,
                    $timesheet->time_in,
                    $timesheet->time_out,
                    $timesheet->task,
                    minutesToHourMinuteFormat($minutesBreaktime),
                    minutesToHourMinuteFormat($total_minutes),
                ]);
            });

            $grandPerEmployeeTotal = 0;
            $perEmployee = $task->groupBy('user_id');
            $totalEmployee = $perEmployee->map(function ($timesheet) use (&$grandPerEmployeeTotal) {
                $breaktime = collect($timesheet->toArray())->pluck(['breaktime']);
                $minutesBreaktime = $breaktime->isNotEmpty() ? $breaktime->sum('duration_in_minutes') : 0;
                $total_minutes = $timesheet->sum('duration_in_minutes') + $minutesBreaktime;
                $grandPerEmployeeTotal += $total_minutes;
                return [$timesheet->first()->user->fullname, minutesToHourMinuteFormat($total_minutes)];
            });

            $grandPerJobTotal = 0;
            $perJob = $task->groupBy('job');
            $totalJob = $perJob->map(function ($timesheet) use (&$grandPerJobTotal) {
                $breaktime = collect($timesheet->toArray())->pluck(['breaktime']);
                $minutesBreaktime = $breaktime->isNotEmpty() ? $breaktime->sum('duration_in_minutes') : 0;
                $total_minutes = $timesheet->sum('duration_in_minutes') + $minutesBreaktime;
                $grandPerJobTotal += $total_minutes;

                return [$timesheet->first()->job, minutesToHourMinuteFormat($total_minutes)];                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
            });

            $retVal[] = ['', '', '', '', '', 'Total', minutesToHourMinuteFormat($grandTotal)];
            $retVal[] = [];
            $retVal[] = ['Employee Total'];
            $retVal = array_merge($retVal, $totalEmployee->toArray());
            $retVal[] = ['Total', minutesToHourMinuteFormat($grandPerEmployeeTotal)];
            $retVal[] = [];
            $retVal[] = ['Job Total'];
            $retVal = array_merge($retVal, $totalJob->toArray());
            $retVal[] = ['Total', minutesToHourMinuteFormat($grandPerJobTotal)];
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
        if ($this->request->has('from')) $this->from = Carbon::parse($this->request->from);
        if ($this->request->has('to')) $this->to = Carbon::parse($this->request->to);

        //query between date range
        $biometrics = Biometric::whereBetween('time_in', [$this->from, $this->to]);
        
        //query where employee in employees
        if ($this->request->has('employees')) {
            $biometrics->whereIn('user_id', $this->request->employees);
        }

        $timesheets = $biometrics->get();
    }
}