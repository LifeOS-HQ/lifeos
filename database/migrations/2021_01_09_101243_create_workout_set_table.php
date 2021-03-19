<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkoutSetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workout_set', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('workout_exercise_id');
            $table->foreignId('exercise_id');
            $table->foreignId('workout_id');

            $table->unsignedSmallInteger('order')->default(0);
            $table->unsignedMediumInteger('weight_in_g')->default(0);
            $table->unsignedMediumInteger('reps_count')->default(0);

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('workout_exercise_id')->references('id')->on('workout_exercise');
            $table->foreign('exercise_id')->references('id')->on('exercises');
            $table->foreign('workout_id')->references('id')->on('workouts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workout_set');
    }
}
