<?php

namespace Tests\Unit\Behaviours;

use App\Models\Behaviours\Behaviour;
use Tests\Unit\TestCase;
use App\Models\Behaviours\History;
use App\Models\Services\Data\Attributes\Attribute;

class HistoryTest extends TestCase
{
   /**
     * @test
     */
    public function it_has_model_paths()
    {
        $model = History::factory()->create();
        $route_parameter = [
            'behaviour' => $model->behaviour_id,
            'history' => $model->id,
        ];

        $routes = [
            'index_path' => strtok(route(History::ROUTE_NAME . '.index', $route_parameter), '?'),
            'create_path' => strtok(route(History::ROUTE_NAME . '.create', $route_parameter), '?'),
            'path' => route(History::ROUTE_NAME . '.show', $route_parameter),
            'edit_path' => route(History::ROUTE_NAME . '.edit', $route_parameter),
        ];

        $this->testModelPaths($model, $routes, [
            'behaviour_id' => $model->behaviour_id,
        ]);
    }

    /**
     * @test
     */
    public function it_creates_values_from_behaviour_attributes_when_created()
    {
        $attribute = Attribute::factory()->create();

        $behaviour = Behaviour::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $behaviour->dataAttributes()->create([
            'user_id' => $this->user->id,
            'attribute_id' => $attribute->id,
            'default_value' => 1,
            'goal_value' => 2,
        ]);

        $history = History::factory()->create([
            'behaviour_id' => $behaviour->id,
            'user_id' => $this->user->id,
        ]);

        $this->assertDatabaseHas('behaviours_histories', [
            'id' => $history->id,
        ]);

        $this->assertCount(1, $history->values);
        $value = $history->values->first();

        $this->assertEquals($this->user->id, $value->user_id);
        $this->assertEquals($attribute->id, $value->attribute_id);
        $this->assertEquals('1,00', $value->number_formatted);
    }

    /**
     * @test
     */
    public function it_finds_or_creates_a_day_before_it_is_created()
    {
        $history = History::factory()->create([
            'start_at' => '2024-10-06 07:00:07',
            'user_id' => $this->user->id,
        ]);

        $this->assertDatabaseHas('days', [
            'date' => '2024-10-06 00:00:00',
            'user_id' => $this->user->id,
        ]);

        $this->assertEquals($history->day_id, $history->day->id);

        $history = History::factory()->create([
            'start_at' => '2024-10-06 09:00:07',
            'user_id' => $this->user->id,
        ]);

        $this->assertDatabaseHas('days', [
            'date' => '2024-10-06 00:00:00',
            'user_id' => $this->user->id,
        ]);

        $this->assertEquals($history->day_id, $history->day->id);
    }

    /**
     * @test
     */
    public function it_knows_its_commit_path()
    {
        $history = History::factory()->create();

        $this->assertEquals(route('behaviours.histories.commit.store', ['history' => $history->id]), $history->commit_path);
    }

    /**
     * @test
     */
    public function it_knows_its_complete_path()
    {
        $history = History::factory()->create();

        $this->assertEquals(route('behaviours.histories.complete.store', ['history' => $history->id]), $history->complete_path);
    }

    /**
     * @test
     */
    public function it_knows_its_audio_path()
    {
        $history = History::factory()->create();

        $this->assertEquals(resource_path('audio/daily.mp3'), $history->audio_path);
    }

    /**
     * @test
     */
    public function it_knows_its_value_path()
    {
        $history = History::factory()->create();

        $this->assertEquals(route('behaviours.histories.values.index', ['history' => $history->id]), $history->value_path);
    }
}
