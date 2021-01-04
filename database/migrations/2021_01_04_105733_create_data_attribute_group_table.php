<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataAttributeGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_attribute_group', function (Blueprint $table) {
            $table->id();

            $table->foreignId('attribute_id');
            $table->foreignId('group_id');

            $table->timestamps();

            $table->foreign('attribute_id')->references('id')->on('data_attributes');
            $table->foreign('group_id')->references('id')->on('data_attributes_groups');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_attribute_group');
    }
}
