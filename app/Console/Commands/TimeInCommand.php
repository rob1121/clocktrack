<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class TimeInCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clocktrack:timein {timein}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminder to clock in';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Notification::send($users, new TimeinReminder($this->argument('timein')));
    }
}
