<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataAttributeServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_attribute_service', function (Blueprint $table) {
            $table->id();

            $table->foreignId('attribute_id');
            $table->foreignId('service_id');

            $table->timestamps();

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
        Schema::dropIfExists('data_attribute_service');
    }
}
