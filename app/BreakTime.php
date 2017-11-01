<?php

namespace App;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class BreakTime extends Model
{
    protected $fillable = [
        'schedule_id',
        'break_in',
        'break_out',
    ];
    
    protected $appends = [
        'duration_in_minutes'
    ];
    
    public function schedule() {
        return $this->belongsTo(Schedule::class);
    }
    
    public function getDurationInMinutesAttribute() {
        $breakIn = Carbon::parse($this->break_in);
        $breakOut = Carbon::parse($this->break_out);

        $minutesLength = $breakIn->diffInMinutes($breakOut);
        
        return $minutesLength;
    }
}
