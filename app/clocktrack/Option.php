<?php namespace App\Clocktrack;

use App\Biometric;
use App\User;
use Carbon\Carbon;

trait Option {
  public static function employees() {
    return User::all()->map(function($employee) {
        return (object)[
            'value' => $employee->id,
            'text' => $employee->fullname,
        ];
    });
  }

  public static function biometrics($from, $to, $user = null) 
  {
    $biometric = new Biometric;
    
    if($user) {
      $user = User::where('id', $user);
      $biometric = $user->with(['biometric' => function($query) use($from, $to) {
        $query->whereBetween('time_in', [$from, $to]);
      }])->first()->biometric;
    } else {
        $biometric = collect($biometric->whereBetween('time_in', [$from, $to])->get());
    }

    return $biometric;
  }

  public static function daysIn(Carbon $start, Carbon $end) 
  {
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