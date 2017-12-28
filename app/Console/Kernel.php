<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\TimeinReminder;
use Carbon\Carbon;
use App\User;
use App\Notifications\TimeoutReminder;
use App\Notif;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $notif = Notif::first();
        if($notif->early_in) $this->timeinReminder($schedule);
        if ($notif->early_out) $this->timeoutReminder($schedule);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * remind to clock in on the set time
     */
    protected function timeinReminder(Schedule $schedule) 
    {
        $users = User::all();
        $users->map(function ($user) use ($schedule) 
        {
            $user->schedule->map(function ($sched) use ($schedule) 
            {
                if ($sched->start_date === Carbon::now()->format(config('constant.dateFormat'))) 
                {
                    $schedTime = Carbon::parse($sched->start_date)->subMinutes(30)->format('H:i');
                    $schedule->job(new TimeinReminder($sched->start_datetime))->dailyAt($schedTime);
                }
            });
        });
    }

    /**
     * remind to clock out on the set time
     */
    protected function timeoutReminder(Schedule $schedule) 
    {
        $users = User::all();
        $users->map(function ($user) use ($schedule) 
        {
            $user->schedule->map(function ($sched) use ($schedule) 
            {
                if ($sched->end_date === Carbon::now()->format(config('constant.dateFormat'))) 
                {
                    $schedTime = Carbon::parse($sched->end_date)->subMinutes(15)->format('H:i');
                    $schedule->job(new TimeoutReminder($sched->end_datetime))->dailyAt($schedTime);
                }
            });
        });
    }
}
