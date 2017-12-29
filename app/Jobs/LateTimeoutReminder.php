<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\EmailLateTimeoutReminder;

class LateTimeoutReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $timeout;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $timeout)
    {
        $this->timeout = $timeout;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $notif = Notif::first();
        $users = "{$notif->recipient},{$this->user}";
        $users = User::find(explode(',', $users));
        Notification::send($users, new EmailLateTimeoutReminder($this->timeout));
    }
}
