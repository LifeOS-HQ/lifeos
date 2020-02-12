<?php

namespace Tests\Feature\Controller\Reviews;

use App\Models\Reviews\Lifearea;
use App\Models\Reviews\Review;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class LifeareaControllerTest extends TestCase
{
    protected $baseRouteName = 'review.lifearea';
    protected $baseViewPath = 'review.lifearea';
    protected $className = Lifearea::class;

    /**
     * @test
     */
    public function guests_can_not_access_the_following_routes()
    {
        $model = factory($this->className)->create();

        $actions = [
            'index' => ['review' => $model->review_id],
            'store' => ['review' => $model->review_id],
            'show' => ['review' => $model->review_id, 'lifearea' => $model->id],
            'edit' => ['review' => $model->review_id, 'lifearea' => $model->id],
            'update' => ['review' => $model->review_id, 'lifearea' => $model->id],
            'destroy' => ['review' => $model->review_id, 'lifearea' => $model->id],
        ];
        $this->guestsCanNotAccess($actions);
    }

    /**
     * @test
     */
    public function a_user_can_not_see_things_from_a_different_user()
    {
        $modelOfADifferentUser = factory($this->className)->create();

        $parameters = [
            'lifearea' => $modelOfADifferentUser->id,
            'review' => $modelOfADifferentUser->review_id,
        ];

        $this->a_user_can_not_see_models_from_a_different_user($parameters);

        $this->a_different_user_gets_a_403('index', 'get', ['review' => $modelOfADifferentUser->review_id]);
    }

    /**
     * @test
     */
    public function a_user_can_get_a_collection_of_models()
    {
        $parent = $this->createParent();

        $models = factory($this->className, 3)->create([
            'user_id' => $this->user->id,
            'review_id' => $parent->id,
        ]);

        $this->getCollection([
            'review' => $parent->id,
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_get_the_model()
    {
        $model = $this->createModel();

        $response = $this->getModel(['review' => $model->review_id, 'lifearea' => $model->id], $model);
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
            'rating' => 5,
            'comment' => 'comment',
        ];

        $response = $this->put(route($this->baseRouteName . '.update', ['review' => $model->review_id, 'lifearea' => $model->id]), $data)
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
        $this->withoutExceptionHandling();

        $model = $this->createModel();

        $this->deleteModel($model, ['review' => $model->review_id, 'lifearea' => $model->id])
            ->assertRedirect();
    }

    protected function createParent() : Model
    {
        return factory(Review::class)->create([
            'user_id' => $this->user->id,
        ]);
    }
}
