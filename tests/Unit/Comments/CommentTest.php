<?php

namespace Tests\Unit\Comments;

use App\Models\Comments\Comment;
use Tests\Unit\TestCase;

class CommentTest extends TestCase
{
    protected $class_name = Comment::class;

    /**
     * @test
     */
    public function it_has_model_paths()
    {
        $model = $this->class_name::factory()->create();
        $route_parameter = [
            'comment' => $model->id,
        ];

        $routes = [
            'path' => route($this->class_name::ROUTE_NAME . '.show', $route_parameter),
        ];

        $this->testModelPaths($model, $routes);
    }
}
