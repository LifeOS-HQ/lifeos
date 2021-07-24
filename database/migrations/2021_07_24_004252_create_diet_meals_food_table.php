<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDietMealsFoodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diet_meals_food', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('diet_meal_id');
            $table->foreignId('food_id');

            $table->unsignedTinyInteger('order_by')->default(0);
            $table->double('amount', 10, 3)->default(0);

            $table->unsignedTinyInteger('rating_points')->default(0);
            $table->text('rating_comment')->nullable();

            $table->double('calories', 10, 3)->default(0);
            $table->double('carbohydrate', 10, 3)->default(0);
            $table->double('fat', 10, 3)->default(0);
            $table->double('protein', 10, 3)->default(0);

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('diet_meal_id')->references('id')->on('diet_meals');
            $table->foreign('food_id')->references('id')->on('food');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diet_meals_food');
    }
}
