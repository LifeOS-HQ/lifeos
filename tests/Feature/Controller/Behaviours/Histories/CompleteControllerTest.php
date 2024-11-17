<?php

namespace Tests\Feature\Controller\Behaviours\Histories;

use Tests\TestCase;
use App\Models\Behaviours\History;
use Illuminate\Database\Eloquent\Model;

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
