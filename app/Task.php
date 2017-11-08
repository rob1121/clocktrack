<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'code',
        'active'
    ];

    public function allowedTaskForJob() {
        return $this->hasMany(AllowedTaskForJob::class);
    }

    public function allowedUserForTask() {
        return $this->hasMany(AllowedUserForTask::class);
    }

    public static function selectOptions() {
        return task::get()->map(function ($task) {
            return (object)[
                'value' => $task->title,
                'text' => $task->title,
            ];
        })->toArray();
    }
}
