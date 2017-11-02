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

Route::get('/timesheet/employee/{user}', 'Timesheet\ByEmployeeController@index')->name('timesheet.by_employee');

Route::resource('timesheet','Timesheet\TimesheetController');
Route::resource('schedule', 'ScheduleController', ['only' => [
    'destroy'    
]]);
