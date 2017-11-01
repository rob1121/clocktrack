<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(User::class);
        $this->call(Task::class);
        $this->call(Job::class);
        $this->call(Schedule::class);
    }
}
