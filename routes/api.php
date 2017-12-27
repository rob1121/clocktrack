<?php

use Illuminate\Http\Request;
use App\Schedule;
use App\User;
use App\Job;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
function schedule($schedules) {
    $schedules = $schedules->map(function($schedule) {
        
        return [
            'id' => $schedule->id, 
            'resourceId' => $schedule->user_id, 
            'scheduleId' => $schedule->id,
            'itemId' => $schedule->id,
            'title' => $schedule->job,
            'body' => $schedule->job_description ?: '',
            'schedule' => Carbon::parse($schedule->start_date)->format('m/d'),
            'start' => "{$schedule->start_date} {$schedule->start_time}",
            'end' => "{$schedule->end_date} {$schedule->end_time}",
            'color' => $schedule->color,
            'deleteUrl' => route('shift.destroy', ['schedule' => $schedule->id]),
            'editUrl' => route('shift.edit', ['schedule' => $schedule->id])
        ];
    });

    return $schedules;
}

Route::get('/schedules', function (Request $request) {
    $start = $request->start ? Carbon::parse($request->start) : Carbon::now()->startOfMonth();
    $start = $start->format(config('constant.dateFormat'));

    $end = $request->end ? Carbon::parse($request->end) : Carbon::now()->endOfMonth();
    $end = $end->format(config('constant.dateFormat'));

    $schedules = Schedule::whereBetween('start_date', [$start, $end]);
    $schedules = $schedules->where('user_id', $request->user);
    $schedules = $schedules->get();

    return schedule($schedules);
})->name('api.schedules');

Route::get('/schedules/all', function (Request $request) {
    $start = $request->start ? Carbon::parse($request->start) : Carbon::now()->startOfMonth();
    $start = $start->format(config('constant.dateFormat'));

    $end = $request->end ? Carbon::parse($request->end) : Carbon::now()->endOfMonth();
    $end = $end->format(config('constant.dateFormat'));

    $schedules = Schedule::whereBetween('start_date', [$start, $end]);
    $schedules = $schedules->get();
    
    return schedule($schedules);
})->name('api.schedules.all');

Route::get('/jobs/all', function (Request $request) {
    $start = $request->start ? Carbon::parse($request->start) : Carbon::now()->startOfMonth();
    $start = $start->format(config('constant.dateFormat'));

    $end = $request->end ? Carbon::parse($request->end) : Carbon::now()->endOfMonth();
    $end = $end->format(config('constant.dateFormat'));

    $jobs = Schedule::whereBetween('start_date', [$start, $end]);
    $jobs = $jobs->get();

    $jobs = $jobs->map(function($job) {
        return [
            'resourceId' => $job->id, 
            'scheduleId' => $job->id,
            'title' => $job->user->fullname,
            'body' => $job->job_description ?: '',
            'schedule' => Carbon::parse($job->start_date)->format('m/d'),
            'start' => "{$job->start_date} {$job->start_time}",
            'end' => "{$job->end_date} {$job->end_time}",
            'color' => $job->color,
            'deleteUrl' => route('shift.destroy', ['schedule' => $job->id]),
            'editUrl' => route('shift.edit', ['schedule' => $job->id])
        ];
    });

    return $jobs;
})->name('api.jobs.all');

Route::get('/employees', function (Request $request) {
    $employees = User::all();
    $employees = $employees->map(function($employee) {
        return [
            'id' => $employee->id,
            'employeeId' => $employee->id,
            'title' => $employee->fullname,
        ];
    });

    return $employees;
})->name('api.employees');



Route::get('/jobs', function (Request $request) {
    $jobs = Job::all();
    $jobs = $jobs->map(function($job) {
        return [
            'id' => $job->id,
            'jobId' => $job->id,
            'title' => $job->title,
            'color' => $job->color,
        ];
    });

    return $jobs;
})->name('api.jobs');



Route::get('/download/timesheet', 'ExcelDownloadController@timesheet')->name('api.jobs');
Route::get('/download/employee-summary', 'ExcelDownloadController@employeeSummary')->name('api.employee_summary');
Route::get('/download/employee-details', 'ExcelDownloadController@employeeDetails')->name('api.employee_details');
Route::get('/download/job-details', 'ExcelDownloadController@jobDetails')->name('api.job_details');
