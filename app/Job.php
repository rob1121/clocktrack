<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = [
        'title',
        'number',
        'description',
        'file',
        'color',
        'track_labor_budget',
        'total_hour_target',
        'track_when_budget_hits',
        'hours_remaining',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'active',
        'remind_clockout',
        'remind_clockin',
    ];
    
    public function allowTaskForJob() {
        return $this->hasMany(AllowTaskForJob::class);
    }
    
    public function allowUserForJob() {
        return $this->belongsTo(AllowUserForJob::class);
    }

    public static function selectOptions() {
        return static::get()->map(function ($job) {
            return (object)[
                'value' => $job->title,
                'text' => $job->title,
            ];
        })->toArray();
    }
}
