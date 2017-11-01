<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = [
        'title',
    ];

    public static function selectOptions() {
        return static::get()->map(function ($job) {
            return (object)[
                'value' => $job->title,
                'text' => $job->title,
            ];
        })->toArray();
    }
}
