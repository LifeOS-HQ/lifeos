<?php

namespace Tests\Feature\Controller\Lifeareas;

use App\Models\Lifeareas\Lifearea;
use App\Models\Lifeareas\Scale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class ScaleControllerTest extends TestCase
{
    protected $baseRouteName = 'lifearea.scale';
    protected $baseViewPath = 'lifearea.scale';
    protected $className = Scale::class;

    /**
     * @test
     */
    public function guests_can_not_access_the_following_routes()
    {
        $model = factory($this->className)->create();

        $actions = [
            'index' => ['lifearea' => $model->lifearea_id],
            'store' => ['lifearea' => $model->lifearea_id],
            'show' => ['lifearea' => $model->lifearea_id, 'scale' => $model->id],
            'edit' => ['lifearea' => $model->lifearea_id, 'scale' => $model->id],
            'update' => ['lifearea' => $model->lifearea_id, 'scale' => $model->id],
            'destroy' => ['lifearea' => $model->lifearea_id, 'scale' => $model->id],
        ];
        $this->guestsCanNotAccess($actions);
    }

    /**
     * @test
     */
    public function a_user_can_not_see_things_from_a_different_user()
    {
        $parent = factory(Lifearea::class)->create();

        $modelOfADifferentUser = $parent->scales->first();

        $parameters = [
            'lifearea' => $modelOfADifferentUser->lifearea_id,
            'scale' => $modelOfADifferentUser->id,
        ];

        $this->a_user_can_not_see_models_from_a_different_user($parameters);

        $this->a_different_user_gets_a_403('index', 'get', ['lifearea' => $modelOfADifferentUser->lifearea_id]);
    }

    /**
     * @test
     */
    public function a_user_can_get_a_collection_of_models()
    {
        $parent = $this->createParent();

        $this->getCollection([
            'lifearea' => $parent->id,
        ], 10);
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
            'description' => 'Description',
        ];

        $response = $this->put(route($this->baseRouteName . '.update', ['lifearea' => $model->lifearea_id, 'scale' => $model->id]), $data)
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas($model->getTable(), [
            'id' => $model->id,
        ] + $data);
    }

    // Hier geht es weiter

    protected function createParent() : Model
    {
        return factory(Lifearea::class)->create([
            'user_id' => $this->user->id,
        ]);
    }
}
