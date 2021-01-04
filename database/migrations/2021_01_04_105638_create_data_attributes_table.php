<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_attributes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('group_id');
            $table->foreignId('type_id');

            $table->string('name');
            $table->string('slug');
            $table->unsignedSmallInteger('priority');

            $table->timestamps();

            // $table->foreign('type_id')->references('id')->on('data_types');
            // $table->foreign('group_id')->references('id')->on('data_attributes_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_attributes');
    }
}
