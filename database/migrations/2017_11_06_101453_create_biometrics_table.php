<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBiometricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('biometrics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('time_in')->nullable();
            $table->string('time_out')->nullable();
            $table->string('job');
            $table->string('job_code');
            $table->string('task');
            $table->string('task_code');
            $table->string('lat');
            $table->string('lng');
            $table->string('file')->nullable();
            $table->boolean('active')->default(false);
            $table->string('notes')->nullable();
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
        Schema::dropIfExists('biometrics');
    }
}
