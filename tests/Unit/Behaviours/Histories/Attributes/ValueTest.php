<?php

namespace Tests\Unit\Behaviours\Histories\Attributes;

use Tests\Unit\TestCase;
use App\Models\Behaviours\Histories\Attributes\Value;

class ValueTest extends TestCase
{
    /**
     * @test
     */
    public function it_has_model_paths()
    {
        $model = Value::factory()->create();
        $route_parameter = [
            'history' => $model->history_id,
            'value' => $model->id,
        ];

        $routes = [
            'index_path' => strtok(route(Value::ROUTE_NAME . '.index', $route_parameter), '?'),
            'create_path' => strtok(route(Value::ROUTE_NAME . '.create', $route_parameter), '?'),
            'path' => route(Value::ROUTE_NAME . '.show', $route_parameter),
            'edit_path' => route(Value::ROUTE_NAME . '.edit', $route_parameter),
        ];

        $this->testModelPaths($model, $routes, [
            'history_id' => $model->history_id,
        ]);
    }
}
