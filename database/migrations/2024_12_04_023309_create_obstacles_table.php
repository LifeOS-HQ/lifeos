<?php

use App\Models\Days\Day;
use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObstaclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('obstacles', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Day::class, 'created_day_id');
            $table->foreignIdFor(Day::class, 'overcome_day_id')->nullable();
            $table->foreignIdFor(Day::class, 'alchemized_day_id')->nullable();
            $table->unsignedSmallInteger('level')->default(1);
            $table->string('title')->nullable();
            $table->string('whish')->nullable();
            $table->text('outcome')->nullable();
            $table->text('obstacle')->nullable();
            $table->text('plan')->nullable();
            $table->text('loot')->nullable();
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('obstacles');
    }
}
