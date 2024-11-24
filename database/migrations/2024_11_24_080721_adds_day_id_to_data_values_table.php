<?php

use App\Models\Days\Day;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddsDayIdToDataValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_values', function (Blueprint $table) {
            $table->foreignIdFor(Day::class)->nullable()->after('attribute_id');
            $table->index('day_id', 'data_values_day_id_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_values', function (Blueprint $table) {
            $table->dropIndex('data_values_day_id_index');
            $table->dropColumn('day_id');
        });
    }
}
