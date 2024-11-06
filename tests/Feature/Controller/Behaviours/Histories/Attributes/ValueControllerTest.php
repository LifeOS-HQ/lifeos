<?php

namespace Tests\Feature\Controller\Behaviours\Histories\Attributes;

use App\User;
use Tests\TestCase;
use Illuminate\Http\Response;
use App\Models\Behaviours\History;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Services\Data\Attributes\Attribute;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Behaviours\Histories\Attributes\Value;

class ValueControllerTest extends TestCase
{
    protected $baseRouteName = 'behaviours.histories.values';
    protected $baseViewPath = 'behaviour.history.value';
    protected $className = Value::class;

    /**
     * @test
     */
    public function guests_can_not_access_the_following_routes()
    {
        $model = $this->className::factory()->create();

        $actions = [
            'index' => ['history' => $model->history_id],
            'store' => ['history' => $model->history_id],
            'show' => ['history' => $model->history_id, 'value' => $model->id],
            'edit' => ['history' => $model->history_id, 'value' => $model->id],
            'update' => ['history' => $model->history_id, 'value' => $model->id],
            'destroy' => ['history' => $model->history_id, 'value' => $model->id],
        ];
        $this->guestsCanNotAccess($actions);
    }

    /**
     * @test
     */
    public function a_user_can_not_see_things_from_a_different_user()
    {
        $modelOfADifferentUser = $this->className::factory()->create();

        $this->a_user_can_not_see_models_from_a_different_user(['history' => $modelOfADifferentUser->history_id, 'value' => $modelOfADifferentUser->id]);
    }

    /**
     * @test
     */
    public function a_user_can_get_a_paginated_collection_of_models()
    {
        $history = History::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $models = $this->className::factory()->times(3)->create([
            'user_id' => $this->user->id,
            'history_id' => $history->id,
        ]);

        $this->getPaginatedCollection([
            'history' => $history->id,
        ]);
    }

     /**
     * @test
     */
    public function an_other_user_can_not_create_a_model()
    {
        $other_user = factory(User::class)->create();

        $history = History::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $attribute = Attribute::factory()->create();

        $data = [
            'attribute_id' => $attribute->id,
            'number_formatted' => '1,23',
        ];

        $this->signIn($other_user);

        $this->postJson(route($this->baseRouteName . '.store', ['history' => $history->id]), $data)
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function a_user_can_create_a_model()
    {
        $history = History::factory()->create([
            'user_id' => 2,
            // 'user_id' => $this->user->id,
        ]);

        $attribute = Attribute::factory()->create();

        $this->signIn();

        $data = [
            'attribute_id' => $attribute->id,
            'number_formatted' => '1,23',
        ];

        $this->postJson(route($this->baseRouteName . '.store', ['history' => $history->id]), $data)
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas((new $this->className)->getTable(), [
            'history_id' => $history->id,
            'attribute_id' => $attribute->id,
            'raw' => '1.23',
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_update_a_model()
    {
        $this->withoutExceptionHandling();

        $history = History::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $model = $this->createModel([
            'history_id' => $history->id,
            'raw' => '1.23',
        ]);

        $this->signIn();

        $data = [
            'number_formatted' => '4,56',
        ];

        $response = $this->put(route($this->baseRouteName . '.update', ['history' => $model->history_id, 'value' => $model->id]), $data)
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas($model->getTable(), [
            'id' => $model->id,
            'raw' => '4.56',
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_delete_a_model()
    {
        $model = $this->createModel();

        $this->deleteModel($model, ['history' => $model->history_id, 'value' => $model->id])
            ->assertRedirect();
    }

    protected function createModel(array $attributes = []): Model
    {
        return Value::factory()->create([
            'user_id' => $this->user->id,
        ] + $attributes);
    }
}
