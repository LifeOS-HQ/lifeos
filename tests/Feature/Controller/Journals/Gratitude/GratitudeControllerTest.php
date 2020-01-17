<?php

namespace Tests\Feature\Controller\Journals\Gratitude;

use App\Models\Journals\Gratitude\Gratitude;
use App\Models\Journals\Journal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class GratitudeControllerTest extends TestCase
{
    protected $baseRouteName = 'journal.gratitude';
    protected $baseViewPath = 'gratitude';
    protected $className = Gratitude::class;

    /**
     * @test
     */
    public function guests_can_not_access_the_following_routes()
    {
        $model = factory($this->className)->create();

        $actions = [
            'index' => ['journal' => $model->journal_id],
            'store' => ['journal' => $model->journal_id],
            'show' => ['journal' => $model->journal_id, 'gratitude' => $model->id],
            'edit' => ['journal' => $model->journal_id, 'gratitude' => $model->id],
            'update' => ['journal' => $model->journal_id, 'gratitude' => $model->id],
            'destroy' => ['journal' => $model->journal_id, 'gratitude' => $model->id],
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
            'gratitude' => $modelOfADifferentUser->id
        ]);

        $this->a_different_user_gets_a_403('index', 'get', ['journal' => $modelOfADifferentUser->journal_id]);
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
        $this->withoutExceptionHandling();

        $parent = $this->createParent();

        $models = factory($this->className, 3)->create([
            'user_id' => $this->user->id,
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

        $this->signIn();

        $data = [
            'text' => 'text',
        ];

        $this->post(route($this->baseRouteName . '.store', ['journal' => $parent->id]), $data)
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas((new $this->className)->getTable(), $data + [
            'user_id' => $this->user->id,
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_get_the_model()
    {
        $model = $this->createModel();

        $response = $this->getModel(['journal' => $model->journal_id, 'gratitude' => $model->id], $model);
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
            'text' => 'updated',
        ];

        $response = $this->put(route($this->baseRouteName . '.update', ['journal' => $model->journal_id, 'gratitude' => $model->id]), $data)
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

        $this->deleteModel($model, ['journal' => $model->journal_id, 'gratitude' => $model->id])
            ->assertRedirect();
    }

    protected function createParent() : Model
    {
        return factory(Journal::class)->create([
            'user_id' => $this->user->id,
        ]);
    }
}
