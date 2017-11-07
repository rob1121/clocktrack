<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AllowUserForTask extends Model
{
    protected $fillable = [
        'task_id', 'user_id',
    ];

    public function task() {
        return $this->hasMany(Task::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
