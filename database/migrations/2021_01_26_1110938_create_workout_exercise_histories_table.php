<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkoutExerciseHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workout_exercise_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('exercise_id');
            $table->foreignId('workout_history_id');

            $table->string('goal_type');
            $table->unsignedMediumInteger('goal_target')->default(0);

            $table->unsignedSmallInteger('order')->default(0);

            $table->timestamps();

            $table->foreign('exercise_id')->references('id')->on('exercises');
            $table->foreign('workout_history_id')->references('id')->on('workout_histories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workout_exercise_histories');
    }
}
