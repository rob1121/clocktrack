<?php

$factory->define(App\Job::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->word,
    ];
});

$factory->define(App\Schedule::class, function (Faker\Generator $faker) {
    $dates = Carbon::parse($faker->dateTimeBetween('-3 days')->format('Y-m-d'));
    return [
        'user_id' => function () {
             return factory(App\User::class)->create()->id;
        },
        'start_date' => $dates->format('Y-m-d'),
        'start_time' => $dates->modify('8 hours')->format('H:i:s'),
        'end_time' => $dates->modify('8 hours')->format('H:i:s'),
        'end_date' => $dates->format('Y-m-d'),
        'job' => $faker->sentence(3),
        'job_description' => $faker->sentence(5),
        'task' => $faker->sentence(3),
        'notes' => $faker->paragraph,
        'file' => $faker->word,
        'active' => 0,
        'lat' => 0,
        'lng' => 0,
    ];
});

$factory->define(App\Task::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->word,
    ];
});

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'phone' => $faker->phoneNumber,
        'email' => $faker->safeEmail,
        'password' => bcrypt($faker->password),
        'remember_token' => str_random(10),
    ];
});

