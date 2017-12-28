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

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Notification::send($users, new EmailLateTimeoutReminder($this->timeout));
    }
}
