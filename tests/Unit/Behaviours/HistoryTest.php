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
}
