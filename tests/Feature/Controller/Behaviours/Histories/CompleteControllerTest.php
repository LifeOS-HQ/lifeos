<?php

namespace Tests\Feature\Controller\Behaviours\Histories;

use Tests\TestCase;
use App\Models\Days\Day;
use App\Models\Services\Service;
use App\Models\Behaviours\History;
use App\Models\Behaviours\Behaviour;
use Illuminate\Database\Eloquent\Model;
use App\Models\Services\Data\Attributes\Attribute;

class CompleteControllerTest extends TestCase
{
    protected $baseRouteName = 'behaviours.histories.complete';
    protected $className = History::class;

    /**
     * @test
     */
    public function guests_can_not_access_the_following_routes()
    {
        $model = $this->className::factory()->create();

        $actions = [
            'store' => ['history' => $model->id],
        ];
        $this->guestsCanNotAccess($actions);
    }

    /**
     * @test
     */
    public function a_user_can_not_see_things_from_a_different_user()
    {
        $modelOfADifferentUser = $this->className::factory()->create();

        $this->signIn();

        $parameters = ['history' => $modelOfADifferentUser->id];

        $this->a_different_user_gets_a_403('store', 'post', $parameters);
    }

    /**
     * @test
     */
    public function a_user_can_update_a_model_as_completed()
    {
        $this->signIn();

        $model = $this->createModel([
            'is_completed' => 0,
            'is_committed' => 0,
        ]);

        $this->assertEquals(0, $model->is_committed);
        $this->assertEquals(0, $model->is_completed);

        $response = $this->postJson($model->complete_path);

        $response->assertOk($model->path);

        $model->refresh();

        $this->assertEquals(1, $model->is_committed);
        $this->assertEquals(1, $model->is_completed);
    }

    /**
     * @test
     */
    public function a_user_can_not_update_a_model_as_completed_if_it_is_completed()
    {
        $this->signIn();

        $model = $this->createModel([
            'is_completed' => 1,
            'is_committed' => 1,
        ]);

        $this->assertEquals(1, $model->is_committed);
        $this->assertEquals(1, $model->is_completed);

        $response = $this->postJson($model->complete_path);

        $response->assertUnprocessable();

        $model->refresh();

        $this->assertEquals(1, $model->is_committed);
        $this->assertEquals(1, $model->is_completed);
    }

    /**
     * @test
     */
    public function the_values_for_the_day_are_calculated_if_the_history_has_some()
    {
        $this->signIn();

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
            'is_completed' => 0,
            'is_committed' => 0,
        ]);

        $history->values()->create([
            'user_id' => $this->user->id,
            'attribute_id' => $attribute->id,
            'raw' => 10,
        ]);

        $this->assertEquals(0, $history->is_committed);
        $this->assertEquals(0, $history->is_completed);

        $response = $this->postJson($history->complete_path);

        $response->assertOk($history->path);

        $history->refresh();

        $this->assertEquals(1, $history->is_committed);
        $this->assertEquals(1, $history->is_completed);

        $this->assertDatabaseHas('data_values', [
            'user_id' => $this->user->id,
            'attribute_id' => $attribute->id,
            'raw' => 10,
            'at' => '2021-01-01 00:00:00',
        ]);
    }

    /**
     * @test
     */
    public function the_values_for_the_day_are_calculated_if_the_model_is_incompleted()
    {
        $this->signIn();

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
            'is_completed' => 1,
            'is_committed' => 1,
        ]);

        $history->values()->create([
            'user_id' => $this->user->id,
            'attribute_id' => $attribute->id,
            'raw' => 10,
        ]);

        $this->assertEquals(1, $history->is_committed);
        $this->assertEquals(1, $history->is_completed);

        $response = $this->deleteJson($history->complete_path);

        $response->assertOk($history->path);

        $history->refresh();

        $this->assertEquals(1, $history->is_committed);
        $this->assertEquals(0, $history->is_completed);

        $this->assertDatabaseMissing('data_values', [
            'user_id' => $this->user->id,
            'attribute_id' => $attribute->id,
            'at' => '2021-01-01 00:00:00',
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_update_a_model_as_not_completed()
    {
        $this->signIn();

        $model = $this->createModel([
            'is_completed' => 1,
            'is_committed' => 1,
        ]);

        $this->assertEquals(1, $model->is_committed);
        $this->assertEquals(1, $model->is_completed);

        $response = $this->deleteJson($model->complete_path);

        $response->assertOk($model->path);

        $model->refresh();

        $this->assertEquals(1, $model->is_committed);
        $this->assertEquals(0, $model->is_completed);
    }

    /**
     * @test
     */
    public function a_user_can_not_update_a_model_as_not_completed_if_it_is_not_completed()
    {
        $this->signIn();

        $model = $this->createModel([
            'is_completed' => 0,
            'is_committed' => 1,
        ]);

        $this->assertEquals(1, $model->is_committed);
        $this->assertEquals(0, $model->is_completed);

        $response = $this->deleteJson($model->complete_path);

        $response->assertUnprocessable();

        $model->refresh();

        $this->assertEquals(1, $model->is_committed);
        $this->assertEquals(0, $model->is_completed);
    }

    protected function createModel(array $attributes = []): Model
    {
        return $this->className::factory()->create([
            'user_id' => $this->user->id,
        ] + $attributes);
    }
}
