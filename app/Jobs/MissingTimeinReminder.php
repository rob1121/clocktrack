<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\EmailMissingTimeinReminder;
use App\Notif;
use App\User;

class MissingTimeinReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $timein;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $timein)
    {
        $this->timein = $timein;
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
        Notification::send($users, new EmailMissingTimeinReminder($this->timein));
    }
}
