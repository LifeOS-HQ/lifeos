<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddsSourceToBehavioursHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('behaviours_histories', function (Blueprint $table) {
            $table->string('source_slug')->nullable()->after('day_id');
            $table->string('source_id')->nullable()->after('source_slug');
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
            $table->dropColumn('source_slug');
            $table->dropColumn('source_id');
        });
    }
}
