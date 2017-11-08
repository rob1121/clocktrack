<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('number')->nullable();
            $table->string('description')->nullable();
            $table->string('file')->nullable();
            $table->string('color')->default('#3097D1');
            $table->boolean('track_labor_budget')->default(false);
            $table->integer('total_hour_target')->default(0);
            $table->boolean('track_when_budget_hits')->default(false);
            $table->integer('hours_remaining')->default(0);
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('postal_code');
            $table->string('country');
            $table->boolean('remind_clockout')->default(false);
            $table->boolean('remind_clockin')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
