<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class AllowedUserForJob extends Model
{
    protected $table = 'allowed_user_for_jobs';
    protected $fillable = [
        'user_id', 'job_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function job() {
        return $this->belongsTo(Job::class);
    }
}
