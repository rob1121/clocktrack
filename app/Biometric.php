<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Biometric extends Model
{
    protected $fillable = [
        'user_id', 'time_in', 'time_out', 'job', ' task', 'notes', 'active', 'lng', 'lat', 'file'
    ];

    protected $appends = [
        'duration_in_minutes', 'start_date'
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function breaktime() {
        return $this->hasMany(BreakTime::class);
    }

    public function scopeActive($query) {
        return $query->where('active', true)->get();
    }
    
    public function getDurationInMinutesAttribute() {
        $start = Carbon::parse($this->time_in);
        $end = Carbon::parse($this->time_out);
        $minutesLength = $start->diffInMinutes($end);
        
        return $minutesLength - $this->breaktime->sum('duration_in_minutes');
    }
    
    public function getStartTimeAttribute() {
        return Carbon::parse($this->time_in)->format('H:s:i');
    }
    
    public function getEndTimeAttribute() {
        return Carbon::parse($this->time_out)->format('H:s:i');
    }
    
    public function getStartDateAttribute() {
        return Carbon::parse($this->time_in)->format('m/d/Y');
    }
    
    public function getEndDateAttribute() {
        return Carbon::parse($this->time_out)->format('m/d/Y');
    }
}
