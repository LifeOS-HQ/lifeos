<?php

use App\User;
use App\Models\Behaviours\Behaviour;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Services\Data\Attributes\Attribute;

class CreateBehavioursAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('behaviours_attributes', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Behaviour::class);
            $table->foreignIdFor(Attribute::class);

            $table->string('service_slug')->default('manual');

            $table->text('default_value')->nullable();
            $table->text('goal_value')->nullable();

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
        Schema::dropIfExists('behaviours_attributes');
    }
}
