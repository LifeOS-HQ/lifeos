<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkingMonthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('working_months', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('year_id');

            $table->date('date');
            $table->unsignedSmallInteger('month');
            $table->unsignedSmallInteger('available_working_days');

            $table->unsignedSmallInteger('days_worked');
            $table->unsignedSmallInteger('workingdays_worked');
            $table->decimal('hours_worked', 6, 2);
            $table->decimal('workingdays_hours_worked', 6, 2);

            $table->unsignedMediumInteger('bonus_in_cents');
            $table->unsignedMediumInteger('gross_in_cents');
            $table->unsignedMediumInteger('net_in_cents');

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('year_id')->references('id')->on('working_years')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('months');
    }
}
