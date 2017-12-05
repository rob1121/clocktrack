<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('clock_in')->default('08:00:00');
            $table->string('clock_out')->default('16:00:00');
            $table->boolean('monday')->default(false);
            $table->boolean('tuesday')->default(false);
            $table->boolean('wednesday')->default(false);
            $table->boolean('thursday')->default(false);
            $table->boolean('friday')->default(false);
            $table->boolean('saturday')->default(false);
            $table->boolean('sunday')->default(false);
            $table->boolean('exclude_admin')->default(true);
            $table->boolean('schedule_remind_clock_in')->default(false);
            $table->boolean('schedule_remind_clock_out')->default(false);
            $table->boolean('schedule_clock_in')->default(false);
            $table->boolean('schedule_clock_out')->default(false);
            $table->boolean('early_in')->default(false);
            $table->boolean('early_out')->default(false);
            $table->boolean('late_in')->default(false);
            $table->boolean('late_out')->default(false);
            $table->boolean('missing_in')->default(false);
            $table->boolean('missing_out')->default(false);
            $table->boolean('unscheduled_time')->default(false);
            $table->boolean('location_tampering')->default(false);
            $table->boolean('send_notification')->default(false);
            $table->string('recipient')->nullable();
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
        Schema::dropIfExists('notifications');
    }
}
