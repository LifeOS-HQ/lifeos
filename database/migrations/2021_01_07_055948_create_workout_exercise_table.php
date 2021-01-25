<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkoutExerciseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workout_exercise', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('exercise_id');
            $table->foreignId('workout_id');

            $table->string('goal_type');
            $table->unsignedMediumInteger('goal_target')->default(0);

            $table->unsignedSmallInteger('order')->default(0);

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('workout_exercise');
    }
}
