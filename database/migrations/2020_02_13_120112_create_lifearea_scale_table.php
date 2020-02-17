<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLifeareaScaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lifearea_scale', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('lifearea_id');

            $table->unsignedSmallInteger('value');
            $table->string('description', 50)->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('lifearea_id')->references('id')->on('lifeareas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scales');
    }
}
