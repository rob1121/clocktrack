<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lastname', 'firstname', 'phone', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    
    protected $appends = [
        'fullname', 'fullname_with_no_comma'
    ];

    public function company() {
        return $this->hasOne(Company::class);
    }
    
    public function allowedUserForJob() {
        return $this->hasMany(AllowedUserForJob::class);
    }
    
    public function allowedUserForTask() {
        return $this->hasMany(AllowedUserForTask::class);
    }

    public function schedule() {
        return $this->hasMany(Schedule::class);
    }

    public function biometric() {
        return $this->hasMany(Biometric::class);
    }

    public function getFullnameAttribute()
    {
        return title_case("{$this->lastname}, {$this->firstname}");
    }
    
    public function getFullnameWithNoCommaAttribute()
    {
        return title_case("{$this->lastname} {$this->firstname}");
    }
}
