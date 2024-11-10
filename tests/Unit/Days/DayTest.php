<?php

namespace Tests\Unit\Days;

use App\Models\Days\Day;
use Tests\Unit\TestCase;

class DayTest extends TestCase
{
    /**
     * @test
     */
    public function it_has_model_paths()
    {
        $model = Day::factory()->create();
        $route_parameter = [
            'day' => $model->id,
        ];

        $routes = [
            'index_path' => strtok(route(Day::ROUTE_NAME . '.index', $route_parameter), '?'),
            'create_path' => strtok(route(Day::ROUTE_NAME . '.create', $route_parameter), '?'),
            'path' => route(Day::ROUTE_NAME . '.show', $route_parameter),
            'edit_path' => route(Day::ROUTE_NAME . '.edit', $route_parameter),
        ];

        $this->testModelPaths($model, $routes);
    }
}
