<?php

use Illuminate\Database\Seeder;

class Schedule extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Schedule::class, 5)->create();
    }
}
