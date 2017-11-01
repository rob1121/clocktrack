<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    const HOURS_IN_MINUTES = 60;

    protected $fillable = [
        'user_id', 'start_date', 'start_time', 'end_date','end_time','job','task','notes','file'
    ];

    protected $appends = [
        'duration_in_minutes'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function breaktime() {
        return $this->hasMany(BreakTime::class);
    }
    
    public function getDurationInMinutesAttribute() {
        $start = Carbon::parse("{$this->start_date}  {$this->start_time}");
        $end = Carbon::parse("{$this->end_date}  {$this->end_time}");
        $minutesLength = $start->diffInMinutes($end);
        
        return $minutesLength - $this->breaktime->sum('duration_in_minutes');
    }

    public static function fetchByDateRange(Carbon $from, Carbon $to) {
        return static::whereBetween('start_date', [
            $from->format('Y-m-d'), 
            $to->format('Y-m-d')
        ])->get();
    }
}
