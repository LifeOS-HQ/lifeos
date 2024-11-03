<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddsMetricsToWorkoutHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workout_histories', function (Blueprint $table) {
            $table->string('source_slug')->nullable()->after('workout_id');
            $table->string('source_id')->nullable()->after('source_slug');

            $table->decimal('ascent_accum', 15, 6)->default(0)->after('end_at');
            $table->decimal('cadence_avg', 15, 6)->default(0)->after('ascent_accum');
            $table->decimal('calories_accum', 15, 6)->default(0)->after('cadence_avg');
            $table->decimal('distance_accum', 15, 6)->default(0)->after('calories_accum');
            $table->decimal('duration_active_accum', 15, 6)->default(0)->after('distance_accum');
            $table->decimal('duration_paused_accum', 15, 6)->default(0)->after('duration_active_accum');
            $table->decimal('duration_total_accum', 15, 6)->default(0)->after('duration_paused_accum');
            $table->decimal('heart_rate_avg', 15, 6)->default(0)->after('duration_total_accum');
            $table->decimal('power_avg', 15, 6)->default(0)->after('duration_total_accum');
            $table->decimal('power_bike_np_last', 15, 6)->default(0)->after('power_avg');
            $table->decimal('power_bike_tss_last', 15, 6)->default(0)->after('power_bike_np_last');
            $table->decimal('speed_avg', 15, 6)->default(0)->after('power_bike_tss_last');
            $table->decimal('work_accum', 15, 6)->default(0)->after('speed_avg');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workout_histories', function (Blueprint $table) {
            $table->dropColumn('source_slug');
            $table->dropColumn('source_id');

            $table->dropColumn('ascent_accum');
            $table->dropColumn('cadence_avg');
            $table->dropColumn('calories_accum');
            $table->dropColumn('distance_accum');
            $table->dropColumn('duration_active_accum');
            $table->dropColumn('duration_paused_accum');
            $table->dropColumn('duration_total_accum');
            $table->dropColumn('heart_rate_avg');
            $table->dropColumn('power_avg');
            $table->dropColumn('power_bike_np_last');
            $table->dropColumn('power_bike_tss_last');
            $table->dropColumn('speed_avg');
            $table->dropColumn('work_accum');
        });
    }
}
