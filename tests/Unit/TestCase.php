<?php

namespace Tests\Unit;

use App\User;
use App\Userfile;
use D15r\ModelPath\Tests\Traits\HasModelPathTests;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Testing\TestResponse;
use Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function testModelPaths(Model $model, array $routes, array $attributes_for_index_path = [])
    {
        foreach ($routes as $route_name => $route) {
            if ($route_name == 'index_path') {
                $class_name = get_class($model);
                $this->testModelPath($class_name::indexPath($attributes_for_index_path), $route);
            }
            $this->testModelPath($model->$route_name, $route);
        }
    }

    protected function testModelPath(string $model_path, string $route) {
        $this->assertEquals($route, $model_path);
    }
}