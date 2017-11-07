<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = [
        'title', 'number','description'
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
