<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLifeareaLevelGoalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lifearea_level_goal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('lifearea_id');
            $table->foreignId('level_id')->index();
            $table->foreignId('data_attribute_id')->nullable();

            $table->decimal('start', 20, 6)->nullable();
            $table->decimal('end', 20, 6)->nullable();

            $table->boolean('is_completed')->default(false);

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('lifearea_id')->references('id')->on('lifeareas');
            $table->foreign('data_attribute_id')->references('id')->on('data_attributes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lifearea_level_goal');
    }
}
