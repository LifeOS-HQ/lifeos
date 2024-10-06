<?php

namespace Tests\Feature\Controller\Behaviours;

use App\Models\Behaviours\Behaviour;
use Illuminate\Http\Response;
use Tests\TestCase;

class BehaviourControllerTest extends TestCase
{
    protected $baseRouteName = 'behaviours';
    protected $baseViewPath = 'behaviour';
    protected $className = Behaviour::class;

    /**
     * @test
     */
    public function guests_can_not_access_the_following_routes()
    {
        $id = $this->className::factory()->create()->id;

        $actions = [
            'index' => [],
            'store' => [],
            'show' => ['behaviour' => $id],
            'edit' => ['behaviour' => $id],
            'update' => ['behaviour' => $id],
            'destroy' => ['behaviour' => $id],
        ];
        $this->guestsCanNotAccess($actions);
    }

    /**
     * @test
     */
    public function a_user_can_not_see_things_from_a_different_user()
    {
        $modelOfADifferentUser = $this->className::factory()->create();

        $this->a_user_can_not_see_models_from_a_different_user(['behaviour' => $modelOfADifferentUser->id]);
    }

    /**
     * @test
     */
    public function a_user_can_see_the_index_view()
    {
        $this->getIndexViewResponse()
            ->assertViewIs($this->baseViewPath . '.index');
    }

    /**
     * @test
     */
    public function a_user_can_get_a_paginated_collection_of_models()
    {
        $this->withoutExceptionHandling();

        $models = $this->className::factory()->times(3)->create([
            'user_id' => $this->user->id,
        ]);

        $this->getPaginatedCollection();
    }

    /**
     * @test
     */
    public function a_user_can_create_a_model()
    {
        $this->signIn();

        $data = [
            'name' => 'Test Name',
        ];

        $this->postJson(route($this->baseRouteName . '.store'), $data)
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas((new $this->className)->getTable(), $data);
    }

    /**
     * @test
     */
    public function a_user_can_see_the_show_view()
    {
        $this->withoutExceptionHandling();

        $model = $this->createModel();

        $this->getShowViewResponse(['behaviour' => $model->id])
            ->assertViewIs($this->baseViewPath . '.show')
            ->assertViewHas('model');
    }

    /**
     * @test
     */
    public function a_user_can_see_the_edit_view()
    {
        $model = $this->createModel();

        $this->getEditViewResponse(['behaviour' => $model->id])
            ->assertViewIs($this->baseViewPath . '.edit')
            ->assertViewHas('model');
    }

    /**
     * @test
     */
    public function a_user_can_update_a_model()
    {
        $this->withoutExceptionHandling();

        $model = $this->createModel();

        $this->signIn();

        $data = [
            'name' => 'Updated Name'
        ];

        $response = $this->put(route($this->baseRouteName . '.update', ['behaviour' => $model->id]), $data)
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

        $this->deleteModel($model, ['behaviour' => $model->id])
            ->assertRedirect();
    }

    protected function createModel(): Behaviour
    {
        return $this->className::factory()->create([
            'user_id' => $this->user->id,
        ]);
    }
}
