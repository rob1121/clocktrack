<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\EmailMissingTimeinReminder;

class MissingTimeinReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $timein;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($timein)
    {
        $this->timein = $timein;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Notification::send($users, new EmailMissingTimeinReminder($this->timein));
    }
}
