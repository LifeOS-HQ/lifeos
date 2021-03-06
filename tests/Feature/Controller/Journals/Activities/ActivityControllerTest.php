<?php

namespace Tests\Feature\Controller\Journals\Activities;

use App\Models\Journals\Activities\Activity;
use App\Models\Journals\Journal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class ActivityControllerTest extends TestCase
{
    protected $baseRouteName = 'journal.activity';
    protected $baseViewPath = 'journal.activity';
    protected $className = Activity::class;

    /**
     * @test
     */
    public function guests_can_not_access_the_following_routes()
    {
        $model = factory($this->className)->create();

        $actions = [
            'index' => ['journal' => $model->journal_id],
            'store' => ['journal' => $model->journal_id],
            'show' => ['journal' => $model->journal_id, 'activity' => $model->id],
            'edit' => ['journal' => $model->journal_id, 'activity' => $model->id],
            'update' => ['journal' => $model->journal_id, 'activity' => $model->id],
            'destroy' => ['journal' => $model->journal_id, 'activity' => $model->id],
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
            'journal' => $modelOfADifferentUser->journal_id,
            'activity' => $modelOfADifferentUser->id
        ]);

        $this->a_different_user_gets_a_403('index', 'get', ['journal' => $modelOfADifferentUser->journal_id]);
    }

    /**
     * @test
     */
    public function a_user_can_get_a_collection_of_models()
    {
        $this->withoutExceptionHandling();

        $parent = $this->createParent();
        $activity = $this->createActivity();

        $models = factory($this->className, 3)->create([
            'user_id' => $this->user->id,
            'activity_id' => $activity->id,
            'journal_id' => $parent->id,
        ]);

        $this->getCollection([
            'journal' => $parent->id,
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_create_a_model()
    {
        $this->withoutExceptionHandling();

        $parent = $this->createParent();
        $activity = $this->createActivity();

        $this->signIn();

        $data = [
            'activity_id' => $activity->id,
        ];

        $this->post(route($this->baseRouteName . '.store', ['journal' => $parent->id]), $data)
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas((new $this->className)->getTable(), $data);
    }

    /**
     * @test
     */
    public function a_user_can_get_the_model()
    {
        $model = $this->createModel();

        $response = $this->getModel(['journal' => $model->journal_id, 'activity' => $model->id], $model);
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
            'activity_id' => $model->activity_id,
            'comment' => 'comment',
            'rating' => 5,
        ];

        $response = $this->put(route($this->baseRouteName . '.update', ['journal' => $model->journal_id, 'activity' => $model->id]), $data)
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

        $this->deleteModel($model, ['journal' => $model->journal_id, 'activity' => $model->id])
            ->assertRedirect();
    }

    protected function createParent() : Model
    {
        return factory(Journal::class)->create([
            'user_id' => $this->user->id,
        ]);
    }

    protected function createActivity() : Model
    {
        return factory(\App\Models\Activities\Activity::class)->create([
            'user_id' => $this->user->id,
        ]);
    }
}
