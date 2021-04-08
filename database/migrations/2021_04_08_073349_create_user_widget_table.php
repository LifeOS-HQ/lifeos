<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserWidgetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_widget', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id');
            $table->string('view')->index();
            $table->string('widget')->index();

            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('column')->default(0);
            $table->unsignedSmallInteger('sort')->default(0);

            $table->json('filters')->nullable();
            $table->json('options')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_widget');
    }
}
