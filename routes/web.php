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
Route::get('/schedule/job/{job}', 'Schedule\ByJobController@index')->name('schedule.by_job');
Route::resource('schedule', 'Schedule\ScheduleController');
Route::resource('dashboard', 'DashboardController', ['only' => ['index']]);
Route::resource('timesheet','Timesheet\TimesheetController', ['only' => ['index']]);
