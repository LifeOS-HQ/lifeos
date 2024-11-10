<?php

use App\Models\Days\Day;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddsDayIdToBehavioursHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('behaviours_histories', function (Blueprint $table) {
            $table->foreignIdFor(Day::class, 'day_id')->nullable()->after('user_id');
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
            $table->dropColumn('day_id');
        });
    }
}
