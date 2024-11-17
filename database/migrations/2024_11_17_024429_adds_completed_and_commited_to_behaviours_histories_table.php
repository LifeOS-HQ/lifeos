<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddsCompletedAndCommitedToBehavioursHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('behaviours_histories', function (Blueprint $table) {
            $table->boolean('is_completed')->default(false)->after('behaviour_id');
            $table->boolean('is_committed')->default(false)->after('is_completed');
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
            $table->dropColumn('is_completed');
            $table->dropColumn('is_committed');
        });
    }
}
