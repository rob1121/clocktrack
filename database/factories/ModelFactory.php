<?php

$factory->define(App\Job::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->word,
        'number' => $faker->randomNumber(),
        'description' => $faker->word,
        'file' => $faker->word,
        'color' => 'red',
        'total_hour_target' => $faker->randomNumber(),
        'address' => $faker->word,
        'city' => $faker->word,
        'state' => $faker->word,
        'postal_code' => $faker->word,
        'country' => $faker->country,
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

$factory->define(App\AllowedTaskForJob::class, function (Faker\Generator $faker) {
    return [
        'job_id' => $faker->randomNumber(),
        'task_id' => function () {
             return factory(App\Task::class)->create()->id;
        },
    ];
});

$factory->define(App\AllowedUserForJob::class, function (Faker\Generator $faker) {
    return [
        'job_id' => $faker->randomNumber(),
        'user_id' => function () {
             return factory(App\User::class)->create()->id;
        },
    ];
});

$factory->define(App\AllowUserForTask::class, function (Faker\Generator $faker) {
    return [
        'user_id' => function () {
             return factory(App\User::class)->create()->id;
        },
    ];
});

$factory->define(App\Biometric::class, function (Faker\Generator $faker) {
    return [
        'user_id' => function () {
             return factory(App\User::class)->create()->id;
        },
        'time_in' => $faker->word,
        'time_out' => $faker->word,
        'job' => $faker->word,
        'task' => $faker->word,
        'lat' => $faker->latitude,
        'lng' => $faker->longitude,
        'file' => $faker->word,
        'active' => $faker->boolean,
        'notes' => $faker->word,
    ];
});

$factory->define(App\BreakTime::class, function (Faker\Generator $faker) {
    return [
        'biometric_id' => $faker->randomNumber(),
        'break_in' => $faker->word,
        'break_out' => $faker->word,
        'schedule_id' => function () {
             return factory(App\Biometric::class)->create()->id;
        },
    ];
});

$factory->define(App\Shift::class, function (Faker\Generator $faker) {
    return [
    ];
});

