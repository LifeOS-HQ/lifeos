<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddsOrdinalColumnToBehavioursHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('behaviours_histories', function (Blueprint $table) {
            $table->unsignedSmallInteger('ordinal')->default(0)->after('behaviour_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('behaviours_histories', function (Blueprint $table) {
            $table->dropColumn('ordinal');
        });
    }
}
