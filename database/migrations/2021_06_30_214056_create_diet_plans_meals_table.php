<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDietPlansMealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diet_plans_meals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('day_id');
            $table->foreignId('plan_id');

            $table->unsignedTinyInteger('order_by')->default(0);

            $table->string('name')->nullable();

            $table->double('calories', 10, 3)->default(0);
            $table->double('carbohydrate', 10, 3)->default(0);
            $table->double('fat', 10, 3)->default(0);
            $table->double('protein', 10, 3)->default(0);

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('day_id')->references('id')->on('diet_plans_days')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('diet_plans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diet_plans_meals');
    }
}
