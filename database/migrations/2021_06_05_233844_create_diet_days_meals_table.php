<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDietDaysMealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diet_days_meals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('day_id');

            $table->dateTime('at')->nullable();
            $table->unsignedTinyInteger('order_by')->default(0);

            $table->string('name')->nullable();

            $table->unsignedTinyInteger('rating_points')->default(0);
            $table->text('rating_comment')->nullable();

            $table->double('calories', 10, 3)->default(0);
            $table->double('carbohydrate', 10, 3)->default(0);
            $table->double('fat', 10, 3)->default(0);
            $table->double('protein', 10, 3)->default(0);

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('day_id')->references('id')->on('diet_days')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diet_days_meals');
    }
}
