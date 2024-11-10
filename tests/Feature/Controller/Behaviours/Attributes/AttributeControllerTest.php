<?php

namespace Tests\Feature\Controller\Behaviours\Attributes;

use App\User;
use Tests\TestCase;
use Illuminate\Http\Response;
use App\Models\Behaviours\Behaviour;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Behaviours\Attributes\Attribute;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttributeControllerTest extends TestCase
{
    protected $baseRouteName = 'behaviours.attributes';
    protected $baseViewPath = 'behaviour.attribute';
    protected $className = Attribute::class;

    /**
     * @test
     */
    public function guests_can_not_access_the_following_routes()
    {
        $model = $this->className::factory()->create();

        $actions = [
            'index' => ['behaviour' => $model->behaviour_id],
            'store' => ['behaviour' => $model->behaviour_id],
            'show' => ['behaviour' => $model->behaviour_id, 'attribute' => $model->id],
            'edit' => ['behaviour' => $model->behaviour_id, 'attribute' => $model->id],
            'update' => ['behaviour' => $model->behaviour_id, 'attribute' => $model->id],
            'destroy' => ['behaviour' => $model->behaviour_id, 'attribute' => $model->id],
        ];
        $this->guestsCanNotAccess($actions);
    }

    /**
     * @test
     */
    public function a_user_can_not_see_things_from_a_different_user()
    {
        $this->markTestIncomplete('Problems with Policy.');

        $user = factory(User::class)->create();

        $behaviour = Behaviour::factory()->create([
            'user_id' => $user->id,
        ]);

        $modelOfADifferentUser = $this->className::factory()->create([
            'user_id' => $user->id,
            'behaviour_id' => $behaviour->id,
        ]);

        var_dump($modelOfADifferentUser->toArray());

        $this->a_user_can_not_see_models_from_a_different_user([
            'behaviour' => $modelOfADifferentUser->behaviour_id,
            'attribute' => $modelOfADifferentUser->id
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_get_a_paginated_collection_of_models()
    {
        $behaviour = Behaviour::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $models = $this->className::factory()->times(3)->create([
            'user_id' => $this->user->id,
            'behaviour_id' => $behaviour->id,
        ]);

        $this->getCollection([
            'behaviour' => $behaviour->id,
        ]);
    }

    /**
     * @test
     */
    public function an_other_user_can_not_create_a_model()
    {
        $other_user = factory(User::class)->create();

        $behaviour = Behaviour::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $attribute = Attribute::factory()->create();

        $data = [
            'attribute_id' => $attribute->id,
        ];

        $this->signIn($other_user);

        $this->postJson(route($this->baseRouteName . '.store', ['behaviour' => $behaviour->id]), $data)
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function a_user_can_create_a_model()
    {
        $this->withoutExceptionHandling();

        $behaviour = Behaviour::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $attribute = Attribute::factory()->create();

        $this->signIn();

        $data = [
            'attribute_id' => $attribute->id,
        ];

        $this->postJson(route($this->baseRouteName . '.store', ['behaviour' => $behaviour->id]), $data)
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas((new $this->className)->getTable(), $data);
    }

    /**
     * @test
     */
    public function a_user_can_update_a_model()
    {
        $this->withoutExceptionHandling();

        $behaviour = Behaviour::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $model = $this->createModel([
            'behaviour_id' => $behaviour->id,
        ]);

        $this->signIn();

        $data = [
            'service_slug' => 'new-service-slug',
            'default_value' => 'new-default-value',
            'goal_value' => 'new-goal-value',
        ];

        $response = $this->put(route($this->baseRouteName . '.update', ['behaviour' => $model->behaviour, 'attribute' => $model->id]), $data)
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas($model->getTable(), [
            'id' => $model->id,
        ] + $data);
    }

    /**
     * @test
     */
    public function a_user_can_delete_a_model()
    {
        $model = $this->createModel();

        $this->deleteModel($model, ['behaviour' => $model->behaviour_id, 'attribute' => $model->id])
            ->assertRedirect();
    }

    protected function createModel(array $attributes = []): Model
    {
        return $this->className::factory()->create([
            'user_id' => $this->user->id,
        ] + $attributes);
    }
}
