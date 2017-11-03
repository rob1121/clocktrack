<?php namespace App\Clocktrack;

use App\Schedule;
use App\User;
use Carbon\Carbon;

trait Option {
  public static function employeesWithSchedule($from, $to, $employee = null) {
    $users = $employee ? User::where('id', $employee) : new User;
    $users = $users->with(['schedule' => function($query) use($from, $to) {
        $query->whereBetween('start_date', [$from, $to]);
    }])->get();

    return $users;
  }

  public static function employees() {
    return User::all()->map(function($employee) {
        return (object)[
            'value' => $employee->id,
            'text' => $employee->fullname,
        ];
    });
  }

  public static function schedules($from, $to, $user = null) 
  {
    $schedules = new Schedule;
    
    if($user) {
      $user = User::where('id', $user);
      $schedules = $user->with(['schedule' => function($query) use($from, $to) {
        $query->whereBetween('start_date', [$from, $to]);
      }])->first()->schedule;
    } else {
        $schedules = $schedules->whereBetween('start_date', [$from, $to])->get();
    }

    return $schedules;
  }

  public static function daysIn(Carbon $start, Carbon $end) 
  {
    Carbon::setWeekStartsAt(Carbon::SUNDAY);
    Carbon::setWeekEndsAt(Carbon::SATURDAY);

    $date = clone $start;
    $week = [];
    $week[] = new Carbon($date);
    
    while($date->diffInDays($end)>0) {
        $date->addDay();
        $week[] = new Carbon($date);
    }
    
    return $week;
  }

  public static function breakTime() {
      $breaktimeOptions = [];
      $date = Carbon::now()->startOfDay();
      $endOfDate = Carbon::now()->endOfDay();
      while ($endOfDate->diffInMinutes($date) > 0) {
          $breaktimeOptions[] =(object) [
              'value' => $date->format(config('constant.timeFormat')),
              'text' => $date->format('h:i a')
          ];
          $date->addMinutes(15);
      }

      return $breaktimeOptions;
  }
}