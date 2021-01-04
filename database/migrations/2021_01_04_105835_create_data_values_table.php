<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_values', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id');
            $table->foreignId('service_id');
            $table->foreignId('attribute_id');

            $table->dateTime('at');
            $table->text('raw')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('attribute_id')->references('id')->on('data_attributes');
            $table->foreign('service_id')->references('id')->on('services');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_values');
    }
}
