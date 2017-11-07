<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AllowedTaskForJob extends Model
{
    protected $table = 'allowed_task_for_jobs';
    protected $fillable = [
        'task_id', 'job_id',
    ];

    public function task() {
        return $this->belongsTo(Task::class);
    }

    public function job() {
        return $this->hasMany(Job::class);
    }
}
