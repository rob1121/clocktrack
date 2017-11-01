<?php

use Illuminate\Database\Seeder;

class Job extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Job::class, 5)->create();
    }
}
