<?php

namespace Tests\Feature\Controller\Reviews;

use App\Models\Reviews\Block;
use App\Models\Reviews\Review;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class BlockControllerTest extends TestCase
{
    protected $baseRouteName = 'review.block';
    protected $baseViewPath = 'block';
    protected $className = Block::class;

    /**
     * @test
     */
    public function guests_can_not_access_the_following_routes()
    {
        $model = factory($this->className)->create();

        $actions = [
            'index' => ['review' => $model->review_id],
            'store' => ['review' => $model->review_id],
            'show' => ['review' => $model->review_id, 'block' => $model->id],
            'edit' => ['review' => $model->review_id, 'block' => $model->id],
            'update' => ['review' => $model->review_id, 'block' => $model->id],
            'destroy' => ['review' => $model->review_id, 'block' => $model->id],
        ];
        $this->guestsCanNotAccess($actions);
    }

    /**
     * @test
     */
    public function a_user_can_not_see_things_from_a_different_user()
    {
        $modelOfADifferentUser = factory($this->className)->create();

        $this->a_user_can_not_see_models_from_a_different_user([
            'review' => $modelOfADifferentUser->review_id,
            'block' => $modelOfADifferentUser->id
        ]);

        $this->a_different_user_gets_a_403('index', 'get', ['review' => $modelOfADifferentUser->review_id]);
    }

    /**
     * @test
     */
    public function a_user_can_get_a_collection_of_models()
    {
        $this->withoutExceptionHandling();

        $parent = $this->createParent();

        $models = factory($this->className, 2)->create([
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
    public function a_user_can_create_a_model()
    {
        $this->withoutExceptionHandling();

        $parent = $this->createParent();

        $this->signIn();

        $data = [

        ];

        $this->post(route($this->baseRouteName . '.store', ['review' => $parent->id]), $data)
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas((new $this->className)->getTable(), $data);
    }

    /**
     * @test
     */
    public function a_user_can_get_the_model()
    {
        $model = $this->createModel();

        $response = $this->getModel(['review' => $model->review_id, 'block' => $model->id], $model);
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
            'title' => 'new title',
            'body' => 'new body',
        ];

        $response = $this->put(route($this->baseRouteName . '.update', ['review' => $model->review_id, 'block' => $model->id]), $data)
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

        $this->deleteModel($model, ['review' => $model->review_id, 'block' => $model->id])
            ->assertRedirect();
    }

    protected function createParent() : Model
    {
        return factory(Review::class)->create([
            'user_id' => $this->user->id,
        ]);
    }
}
