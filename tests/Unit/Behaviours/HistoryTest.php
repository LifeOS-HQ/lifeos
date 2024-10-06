<?php

namespace Tests\Unit\Behaviours;

use Tests\Unit\TestCase;
use App\Models\Behaviours\History;

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
}
