<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notif extends Model
{
    
    protected $table = 'notifications';

    protected $fillable = [
        'clock_in',
        'clock_out',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
        'exclude_admin',
        'schedule_remind_clock_in',
        'schedule_remind_clock_out',
        'schedule_clock_in',
        'schedule_clock_out',
    ];
}
