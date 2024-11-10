<?php

namespace Tests\Feature\Controller\Days;

use Tests\TestCase;
use App\Models\Days\Day;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DayControllerTest extends TestCase
{
    protected $baseRouteName = 'days';
    protected $baseViewPath = 'day';
    protected $className = Day::class;

    /**
     * @test
     */
    public function guests_can_not_access_the_following_routes()
    {
        $model = $this->className::factory()->create();

        $actions = [
            'index' => [],
            'store' => [],
            'show' => ['day' => $model->id],
            'edit' => ['day' => $model->id],
            'update' => ['day' => $model->id],
            'destroy' => ['day' => $model->id],
        ];
        $this->guestsCanNotAccess($actions);
    }

    /**
     * @test
     */
    public function a_user_can_not_see_things_from_a_different_user()
    {
        $modelOfADifferentUser = $this->className::factory()->create();

        $this->a_user_can_not_see_models_from_a_different_user(['day' => $modelOfADifferentUser->id]);
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
        $models = $this->className::factory()->times(3)->create([
            'user_id' => $this->user->id
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
            'date_formatted' => '10.11.2024',
        ];

        $this->postJson(route($this->baseRouteName . '.store'), $data)
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas((new $this->className)->getTable(), [
            'date' => '2024-11-10',
        ]);
    }

    /**
     * @test
     */
    public function a_model_is_not_created_if_one_with_the_same_date_exists()
    {
        $this->signIn();

        $day = Day::factory()->create([
            'date' => '2024-11-10',
            'user_id' => $this->user->id,
        ]);

        $this->assertDatabaseHas((new $this->className)->getTable(), [
            'date' => '2024-11-10',
        ]);

        $data = [
            'date_formatted' => '10.11.2024',
        ];

        $this->postJson(route($this->baseRouteName . '.store'), $data)
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseCount((new $this->className)->getTable(), 1);
    }

    /**
     * @test
     */
    public function a_user_can_see_the_show_view()
    {
        $model = $this->createModel();

        $this->getShowViewResponse(['day' => $model->id])
            ->assertViewIs($this->baseViewPath . '.show')
            ->assertViewHas('model');
    }

    /**
     * @test
     */
    public function a_user_can_see_the_edit_view()
    {
        $model = $this->createModel();

        $this->getEditViewResponse(['day' => $model->id])
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

        ];

        $response = $this->put(route($this->baseRouteName . '.update', ['day' => $model->id]), $data)
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

        $this->deleteModel($model, ['day' => $model->id])
            ->assertRedirect();
    }

    protected function createModel(): Model
    {
        return $this->className::factory()->create([
            'user_id' => $this->user->id,
        ]);
    }
}
