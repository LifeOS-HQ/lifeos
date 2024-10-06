<?php

namespace Tests\Feature\Controller\Behaviours;

use Tests\TestCase;
use Illuminate\Http\Response;
use App\Models\Behaviours\History;
use App\Models\Behaviours\Behaviour;
use Illuminate\Database\Eloquent\Model;

class HistoryControllerTest extends TestCase
{
    protected $baseRouteName = 'behaviours.histories';
    protected $baseViewPath = 'behaviour.history';
    protected $className = History::class;

    /**
     * @test
     */
    public function guests_can_not_access_the_following_routes()
    {
        $model = $this->className::factory()->create();

        $actions = [
            'index' => ['behaviour' => $model->behaviour_id],
            'store' => ['behaviour' => $model->behaviour_id],
            'show' => ['behaviour' => $model->behaviour_id, 'history' => $model->id],
            'edit' => ['behaviour' => $model->behaviour_id, 'history' => $model->id],
            'update' => ['behaviour' => $model->behaviour_id, 'history' => $model->id],
            'destroy' => ['behaviour' => $model->behaviour_id, 'history' => $model->id],
        ];
        $this->guestsCanNotAccess($actions);
    }

    /**
     * @test
     */
    public function a_user_can_not_see_things_from_a_different_user()
    {
        $modelOfADifferentUser = $this->className::factory()->create();

        $this->a_user_can_not_see_models_from_a_different_user(['behaviour' => $modelOfADifferentUser->behaviour_id, 'history' => $modelOfADifferentUser->id]);
    }

    /**
     * @test
     */
    public function a_user_can_see_the_index_view()
    {
        $this->getIndexViewResponse([
            'behaviour' => $this->createModel()->behaviour_id,
        ])
            ->assertViewIs($this->baseViewPath . '.index');
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

        $this->getPaginatedCollection([
            'behaviour' => $behaviour->id,
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_create_a_model()
    {
        $behaviour = Behaviour::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->signIn();

        $data = [
            'end_at' => now()->format('Y-m-d H:i:s'),
            'comment' => 'Test comment',
        ];

        $this->postJson(route($this->baseRouteName . '.store', ['behaviour' => $behaviour->id]), $data)
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas((new $this->className)->getTable(), $data);
    }

    /**
     * @test
     */
    public function a_user_can_see_the_show_view()
    {
        $model = $this->createModel();

        $this->getShowViewResponse(['behaviour' => $model->behaviour_id, 'history' => $model->id])
            ->assertViewIs($this->baseViewPath . '.show')
            ->assertViewHas('model');
    }

    /**
     * @test
     */
    public function a_user_can_see_the_edit_view()
    {
        $model = $this->createModel();

        $this->getEditViewResponse(['behaviour' => $model->behaviour_id, 'history' => $model->id])
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
            'start_at' => now()->format('Y-m-d H:i:s'),
            'end_at' => now()->format('Y-m-d H:i:s'),
            'comment' => 'Test comment',
        ];

        $response = $this->put(route($this->baseRouteName . '.update', ['behaviour' => $model->behaviour_id, 'history' => $model->id]), $data)
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

        $this->deleteModel($model, ['behaviour' => $model->behaviour_id, 'history' => $model->id])
            ->assertRedirect();
    }

    protected function createModel(): Model
    {
        return History::factory()->create([
            'user_id' => $this->user->id,
        ]);
    }
}
