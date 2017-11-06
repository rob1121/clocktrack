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

Route::get('timeclock/{user}/timesheet', 'TimeclockController@timesheet')->name('timeclock.timesheet');
Route::get('timeclock/calendar', 'TimeclockController@calendar')->name('timeclock.calendar');

Route::post('dashboard/{biometric}', 'DashboardController@forceClockout')->name('dashboard.force_clockout');
Route::resource('dashboard', 'DashboardController', ['only' => ['index']]);
Route::resource('breaktime', 'BreaktimeController', ['only' => ['store', 'update']]);
Route::resource('timeclock', 'TimeclockController', ['only' => ['store', 'update']]);
Route::resource('timesheet','Timesheet\TimesheetController', ['only' => ['index']]);
Route::post('shift/store-batch','ShiftController@storeBatch')->name('shift.batch_Store');
Route::resource('shift','ShiftController', ['only' => ['index']]);
