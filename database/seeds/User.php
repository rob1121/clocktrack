<?php

use Illuminate\Database\Seeder;

class User extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\User::class)->create([
            'firstname' => 'robinson',
            'lastname' => 'legaspi',
            'email' => 'robinson.legaspi@maximintegrated.com',
            'password' => Hash::make('password'),
        ]);
    }
}
