<?php

use App\Models\Days\Day;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddsDayIdToDietDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('diet_days', function (Blueprint $table) {
            $table->foreignIdFor(Day::class)->nullable()->after('user_id');
            $table->index('day_id', 'diet_days_day_id_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('diet_days', function (Blueprint $table) {
            $table->dropIndex('diet_days_day_id_index');
            $table->dropColumn('day_id');
        });
    }
}
