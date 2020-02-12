<?php

namespace Tests\Feature\Controller\Lifeareas;

use App\Models\Lifeareas\Lifearea;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class LifeareaControllerTest extends TestCase
{
    protected $baseRouteName = 'lifearea';
    protected $baseViewPath = 'lifearea';
    protected $className = Lifearea::class;

    /**
     * @test
     */
    public function guests_can_not_access_the_following_routes()
    {
        $id = factory($this->className)->create()->id;

        $actions = [
            'index' => [],
            'store' => [],
            'show' => ['lifearea' => $id],
            'edit' => ['lifearea' => $id],
            'update' => ['lifearea' => $id],
            'destroy' => ['lifearea' => $id],
        ];
        $this->guestsCanNotAccess($actions);
    }

    /**
     * @test
     */
    public function a_user_can_not_see_things_from_a_different_user()
    {
        $modelOfADifferentUser = factory($this->className)->create();

        $this->a_user_can_not_see_models_from_a_different_user(['lifearea' => $modelOfADifferentUser->id]);
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
    public function a_user_can_get_a_collection_of_models()
    {
        $models = factory($this->className, 3)->create([
            'user_id' => $this->user->id,
        ]);

        $this->getCollection();
    }

    /**
     * @test
     */
    public function a_user_can_create_a_model()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $data = [
            'title' => 'Title',
        ];

        $this->post(route($this->baseRouteName . '.store'), $data)
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

        $this->getShowViewResponse(['lifearea' => $model->id])
            ->assertViewIs($this->baseViewPath . '.show')
            ->assertViewHas('model');
    }

    /**
     * @test
     */
    public function a_user_can_see_the_edit_view()
    {
        $model = $this->createModel();

        $this->getEditViewResponse(['lifearea' => $model->id])
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
            'title' => 'Updated title',
        ];

        $response = $this->put(route($this->baseRouteName . '.update', ['lifearea' => $model->id]), $data)
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

        $this->deleteModel($model, ['lifearea' => $model->id])
            ->assertRedirect();
    }
}
