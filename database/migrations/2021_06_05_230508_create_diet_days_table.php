<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDietDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diet_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');

            $table->dateTime('at');
            $table->unsignedTinyInteger('rating_points')->default(0);
            $table->text('rating_comment')->nullable();

            $table->double('calories', 6, 3)->default(0);
            $table->double('carbohydrate', 6, 3)->default(0);
            $table->double('fat', 6, 3)->default(0);
            $table->double('protein', 6, 3)->default(0);

            $table->double('goal_calories', 6, 3)->default(0);
            $table->double('goal_carbohydrate', 6, 3)->default(0);
            $table->double('goal_fat', 6, 3)->default(0);
            $table->double('goal_protein', 6, 3)->default(0);

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diet_days');
    }
}
