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

    public function allowTaskForJob() {
        return $this->belongsTo(AllowTaskForJob::class);
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
