<?php

namespace Tests\Feature\Controller\Behaviours\Histories;

use Tests\TestCase;
use App\Models\Behaviours\History;
use Illuminate\Database\Eloquent\Model;

class CommitControllerTest extends TestCase
{
    protected $baseRouteName = 'behaviours.histories.commit';
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
    public function a_user_can_update_a_model_as_committed()
    {
        $this->signIn();

        $model = $this->createModel([
            'is_completed' => 0,
            'is_committed' => 0,
        ]);

        $this->assertEquals(0, $model->is_committed);

        $response = $this->postJson($model->commit_path);

        $response->assertOk($model->path);

        $model->refresh();

        $this->assertEquals(1, $model->is_committed);
    }

    /**
     * @test
     */
    public function a_user_can_not_update_a_model_as_committed_if_it_is_completed()
    {
        $this->signIn();

        $model = $this->createModel([
            'is_completed' => 1,
            'is_committed' => 0,
        ]);

        $this->assertEquals(0, $model->is_committed);

        $response = $this->postJson($model->commit_path);

        $response->assertUnprocessable($model->path);

        $model->refresh();

        $this->assertEquals(0, $model->is_committed);
    }

    protected function createModel(array $attributes = []): Model
    {
        return $this->className::factory()->create([
            'user_id' => $this->user->id,
        ] + $attributes);
    }
}
