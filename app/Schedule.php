<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    const HOURS_IN_MINUTES = 60;

    protected $fillable = [
        'user_id',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'job',
        'notes',
    ];

    protected $appends = [
        'duration_in_minutes', 'start_datetime', 'end_datetime'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function getDurationInMinutesAttribute() {
        $start = Carbon::parse("{$this->start_date}  {$this->start_time}");
        $end = Carbon::parse("{$this->end_date}  {$this->end_time}");
        $minutesLength = $start->diffInMinutes($end);
        
        return $minutesLength;
    }

    public function getStartDatetimeAttribute() {
        $date = null;
        if($this->start_date && $this->start_time) 
        {
            $date = "{$this->start_date} {$this->start_time}";
        }
        return $date;
    }

    public function getEndDatetimeAttribute() {
        $date = null;
        if($this->end_date && $this->end_time) 
        {
            $date = "{$this->end_date} {$this->end_time}";
        }
        return $date;
    }
}
