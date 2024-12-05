<?php

namespace Tests\Unit\Obstacles;

use App\Models\Obstacles\Obstacle;
use Tests\Unit\TestCase;

class ObstacleTest extends TestCase
{
    /**
     * @test
     */
    public function it_has_model_paths()
    {
        $model = Obstacle::factory()->create();
        $route_parameter = [
            'obstacle' => $model->id,
        ];

        $routes = [
            'index_path' => strtok(route(Obstacle::ROUTE_NAME . '.index', $route_parameter), '?'),
            'create_path' => strtok(route(Obstacle::ROUTE_NAME . '.create', $route_parameter), '?'),
            'path' => route(Obstacle::ROUTE_NAME . '.show', $route_parameter),
            'edit_path' => route(Obstacle::ROUTE_NAME . '.edit', $route_parameter),
        ];

        $this->testModelPaths($model, $routes);
    }
}
