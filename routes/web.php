<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();



Route::get('/schedule/employee/{user}', 'Schedule\ByEmployeeController@index')->name('schedule.by_employee');
Route::get('/schedule/job', 'Schedule\ByJobController@index')->name('schedule.by_job');
Route::resource('schedule', 'Schedule\ScheduleController');

Route::resource('biometric', 'BiometricController');
Route::resource('notification', 'NotifController');

Route::get('timeclock/{user}/timesheet', 'TimeclockController@timesheet')->name('timeclock.timesheet');
Route::get('timeclock/calendar', 'TimeclockController@calendar')->name('timeclock.calendar');

Route::post('dashboard/{biometric}', 'DashboardController@forceClockout')->name('dashboard.force_clockout');
Route::resource('dashboard', 'DashboardController', ['only' => ['index']]);
Route::resource('breaktime', 'BreaktimeController', ['only' => ['store', 'update']]);
Route::resource('timeclock', 'TimeclockController', ['only' => ['store', 'update']]);
Route::resource('timesheet','Timesheet\TimesheetController', ['only' => ['index']]);
Route::put('shift/update','ShiftController@update')->name('shift.batch_update');
Route::resource('shift','ShiftController');
Route::resource('employee','EmployeeController', ['except' => ['show']]);


Route::put('job/{job}/is-active', 'JobController@isActive')->name('job.is_active');
Route::resource('job','JobController',['except' => 'show']);

Route::put('task/{task}/is-active', 'TaskController@isActive')->name('task.is_active');
Route::resource('task','TaskController',['except' => 'show']);
