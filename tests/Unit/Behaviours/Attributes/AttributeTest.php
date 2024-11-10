<?php

namespace Tests\Unit\Behaviours\Attributes;

use App\Models\Behaviours\Attributes\Attribute;
use Tests\Unit\TestCase;

class AttributeTest extends TestCase
{
    /**
     * @test
     */
    public function it_has_model_paths()
    {
        $model = Attribute::factory()->create();
        $route_parameter = [
            'behaviour' => $model->behaviour_id,
            'attribute' => $model->id,
        ];

        $routes = [
            'index_path' => strtok(route(Attribute::ROUTE_NAME . '.index', $route_parameter), '?'),
            'path' => route(Attribute::ROUTE_NAME . '.show', $route_parameter),
        ];

        $this->testModelPaths($model, $routes, [
            'behaviour_id' => $model->behaviour_id,
        ]);
    }
}
