<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkoutSetHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workout_set_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('workout_history_id');
            $table->foreignId('workout_exercise_history_id');

            $table->unsignedSmallInteger('order')->default(0);
            $table->unsignedMediumInteger('weight_in_g')->default(0);
            $table->unsignedMediumInteger('reps_count')->default(0);

            $table->boolean('is_completed')->default(false);

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('workout_history_id')->references('id')->on('workout_histories');
            $table->foreign('workout_exercise_history_id')->references('id')->on('workout_exercise_histories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workout_set_histories');
    }
}
