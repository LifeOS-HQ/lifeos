<?php

namespace Tests\Unit\Days;

use App\Models\Days\Day;
use Tests\Unit\TestCase;
use App\Models\Services\Service;
use App\Models\Behaviours\Behaviour;
use App\Models\Services\Data\Attributes\Attribute;

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

    /**
     * @test
     */
    public function it_can_calculate_its_attibute_values()
    {
        $service = factory(Service::class)->create([
            'slug' => 'exist',
            'type' => 'password',
        ]);

        $attribute = Attribute::factory()->create([
            'name' => 'Test Attribute',
            'slug' => 'test-attribute',
        ]);

        $day = Day::factory()->create([
            'user_id' => $this->user->id,
            'date' => '2021-01-01',
        ]);

        $behaviour = Behaviour::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Behaviour',
        ]);

        $history = $behaviour->histories()->create([
            'user_id' => $this->user->id,
            'day_id' => $day->id,
            'start_at' => '2021-01-01 08:00:00',
            'end_at' => '2021-01-01 09:00:00',
            'is_completed' => true,
            'is_committed' => true,
        ]);

        $history->values()->create([
            'user_id' => $this->user->id,
            'attribute_id' => $attribute->id,
            'raw' => 10,
        ]);

        $history = $behaviour->histories()->create([
            'user_id' => $this->user->id,
            'day_id' => $day->id,
            'start_at' => '2021-01-01 08:00:00',
            'end_at' => '2021-01-01 09:00:00',
            'is_completed' => true,
            'is_committed' => true,
        ]);

        $history->values()->create([
            'user_id' => $this->user->id,
            'attribute_id' => $attribute->id,
            'raw' => 20,
        ]);

        $day->calculateAttributeValues();

        $this->assertDatabaseHas('data_values', [
            'user_id' => $this->user->id,
            'attribute_id' => $attribute->id,
            'raw' => 30,
        ]);
    }
}
