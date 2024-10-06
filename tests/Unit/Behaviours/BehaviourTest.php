<?php

namespace Tests\Unit\Behaviours;

use App\Models\Behaviours\Behaviour;
use Tests\Unit\TestCase;

class BehaviourTest extends TestCase
{
    /**
     * @test
     */
    public function it_has_model_paths()
    {
        $model = Behaviour::factory()->create();
        $route_parameter = [
            'behaviour' => $model->id,
        ];

        $routes = [
            'index_path' => strtok(route(Behaviour::ROUTE_NAME . '.index', $route_parameter), '?'),
            'create_path' => strtok(route(Behaviour::ROUTE_NAME . '.create', $route_parameter), '?'),
            'path' => route(Behaviour::ROUTE_NAME . '.show', $route_parameter),
            'edit_path' => route(Behaviour::ROUTE_NAME . '.edit', $route_parameter),
        ];

        $this->testModelPaths($model, $routes);
    }
}
