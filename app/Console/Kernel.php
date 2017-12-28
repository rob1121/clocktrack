<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\TimeinReminder;
use Carbon\Carbon;
use App\User;
use App\Notifications\TimeoutReminder;
use App\Notif;
use App\Biometric;
use App\Jobs\MissingTimeoutReminder;
use App\Jobs\MissingTimeinReminder;
use App\Jobs\LateTimeoutReminder;
use App\Jobs\LateTimeinReminder;

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

    protected $schedule;

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $this->schedule = $schedule;
        $notif = Notif::first();
        if($notif->early_in) $this->timeinReminder();
        if ($notif->early_out) $this->timeoutReminder();
        if ($notif->late_in) $this->lateTimeinReminder();
        if ($notif->late_out) $this->lateTimeoutReminder();
        if ($notif->missing_in) $this->missTimeinReminder();
        if ($notif->missing_out) $this->missTimeoutReminder();
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
    protected function timeinReminder() 
    {
        $users = User::all();
        $users->map(function ($user)
        {
            $user->schedule->map(function ($sched)
            {
                if ($sched->start_date === Carbon::now()->toDateString()) 
                {
                    $schedTime = Carbon::parse($sched->start_date)->subMinutes(15)->format('H:i');
                    $this->schedule->job(new TimeinReminder($sched->start_datetime))->dailyAt($schedTime);
                }
            });
        });
    }

    /**
     * remind to clock out on the set time
     */
    protected function timeoutReminder() 
    {
        $users = User::all();
        $users->map(function ($user)
        {
            $user->schedule->map(function ($sched)
            {
                if ($sched->end_date === Carbon::now()->toDateString()) 
                {
                    $schedTime = Carbon::parse($sched->end_date)->subMinutes(15)->format('H:i');
                    $this->schedule->job(new TimeoutReminder($sched->end_datetime))->dailyAt($schedTime);
                }
            });
        });
    }

    /**
     * notify late timein
     *
     * @return void
     */
    protected function lateTimeinReminder() 
    {
        $users = User::all();
        $users->map(function ($user)
        {
            $user->schedule->map(function ($sched) use($user)
            {
                if ($sched->start_date === Carbon::now()->toDateString()) 
                {
                    $schedTime = Carbon::parse($sched->start_datetime)->addMinutes(15)->toDateTimeString();
                    $biometric = Biometric::whereDate('time_in', '<', $schedTime);
                    $biometric = $biometric->whereDate('time_in', '>', Carbon::now()->startOfDay()->toDateTimeString());
                    $biometric = $biometric->whereuserId($user->id);
                    $biometric->get();

                    if($biometric->isEmpty())
                    {
                        $this->schedule->job(new LateTimeinReminder($sched->start_datetime))->dailyAt($schedTime);
                    }
                }
            });
        });
    }

    /**
     * notify late timein
     *
     * @return void
     */
    protected function lateTimeoutReminder() 
    {
        $users = User::all();
        $users->map(function ($user)
        {
            $user->schedule->map(function ($sched) use($user)
            {
                if ($sched->end_date === Carbon::now()->toDateString()) 
                {
                    $startSchedTime = Carbon::parse($sched->start_datetime)->toDateTimeString();
                    $endSchedTime = Carbon::parse($sched->end_datetime)->addMinutes(15)->toDateTimeString();
                    $biometric = Biometric::whereDate('time_out', '<', $schedTime);
                    $biometric = $biometric->whereDate('time_out', '>', $startSchedTime);
                    $biometric = $biometric->whereuserId($user->id);
                    $biometric->get();

                    if($biometric->isEmpty())
                    {
                        $this->schedule->job(new LateTimeoutReminder($sched->end_datetime))->dailyAt($schedTime);
                    }
                }
            });
        });
    }

    /**
     * notify miss timein
     *
     * @return void
     */
    protected function missTimeinReminder() 
    {
        $users = User::all();
        $users->map(function ($user)
        {
            $user->schedule->map(function ($sched) use($user)
            {
                if ($sched->start_date === Carbon::now()->toDateString()) 
                {
                    $schedTime = Carbon::parse($sched->start_datetime)->addHours(4)->toDateTimeString();
                    $biometric = Biometric::whereDate('time_in', '<', $schedTime);
                    $biometric = $biometric->whereDate('time_in', '>', Carbon::now()->startOfDay()->toDateTimeString());
                    $biometric = $biometric->whereuserId($user->id);
                    $biometric->get();

                    if($biometric->isEmpty())
                    {
                        $this->schedule->job(new MissingTimeinReminder($sched->start_datetime))->dailyAt($schedTime);
                    }
                }
            });
        });
    }

    /**
     * notify miss timein
     *
     * @return void
     */
    protected function missTimeoutReminder() 
    {
        $users = User::all();
        $users->map(function ($user)
        {
            $user->schedule->map(function ($sched) use($user)
            {
                if ($sched->end_date === Carbon::now()->toDateString()) 
                {
                    $startSchedTime = Carbon::parse($sched->start_datetime)->toDateTimeString();
                    $endSchedTime = Carbon::parse($sched->end_datetime)->addHours(4)->toDateTimeString();

                    $biometric = Biometric::whereDate('time_out', '<', $endSchedTime);
                    $biometric = $biometric->whereDate('time_out', '>', $startSchedTime);
                    $biometric = $biometric->whereuserId($user->id);
                    $biometric->get();

                    if($biometric->isEmpty())
                    {
                        $this->schedule->job(new MissingTimeoutReminder($sched->end_datetime))->dailyAt($schedTime);
                    }
                }
            });
        });
    }
}
