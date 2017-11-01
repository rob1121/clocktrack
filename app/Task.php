<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
    ];

    public static function selectOptions() {
        return task::get()->map(function ($task) {
            return (object)[
                'value' => $task->title,
                'text' => $task->title,
            ];
        })->toArray();
    }
}
