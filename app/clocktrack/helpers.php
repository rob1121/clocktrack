<?php 

use Carbon\Carbon;

function minutesToHourMinuteFormat($totalMinutes) {
  $hours = floor($totalMinutes / Carbon::MINUTES_PER_HOUR);
  $minutes = $totalMinutes % Carbon::MINUTES_PER_HOUR;

  return sprintf("%02d:%02d", $hours, $minutes);
}

function hourMinuteFormat($date) {
  return Carbon::parse($date)->format('h:i a');
}

function breakTimeFormat($in, $out, Carbon $start, Carbon $end) {
  $breakIn = Carbon::parse($start->format('Y-m-d') . " {$in}");
  $breakOut = Carbon::parse($start->format('Y-m-d') . " {$out}");
  
  $timeIn = Carbon::parse($start->format('Y-m-d H:i:s'));
  $timeOut = Carbon::parse($end->format('Y-m-d H:i:s'));
  
  $endOfDayOfTimeIn = clone $timeIn;
  $endOfDayOfTimeIn->endOfDay();

  if(!$breakIn->between($timeIn, $endOfDayOfTimeIn)) {
    $breakIn = Carbon::parse($end->format('Y-m-d') . " {$in}");
  }
  
  if(!$breakOut->between($timeIn, $endOfDayOfTimeIn)) {
    $breakOut = Carbon::parse($end->format('Y-m-d') . " {$out}");
  }
  return (object)[
    'in' => $breakIn,
    'out' => $breakOut,
  ];
}