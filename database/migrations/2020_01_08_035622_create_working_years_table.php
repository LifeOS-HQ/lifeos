<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkingYearsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('working_years', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');

            $table->date('date');
            $table->unsignedSmallInteger('year');
            $table->unsignedSmallInteger('available_working_days');
            $table->unsignedMediumInteger('planned_working_hours');

            $table->unsignedSmallInteger('days_worked');
            $table->decimal('hours_worked', 6, 2);

            $table->unsignedMediumInteger('wage_in_cents');
            $table->unsignedMediumInteger('wage_bonus_in_cents');

            $table->unsignedMediumInteger('bonus_in_cents');

            $table->unsignedMediumInteger('gross_in_cents');
            $table->unsignedMediumInteger('net_in_cents');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('years');
    }
}
