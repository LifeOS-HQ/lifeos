<?php

namespace Tests\Feature\Controller\Days\Histories;

use Tests\TestCase;
use App\Models\Days\Day;
use Illuminate\Http\Response;
use App\Models\Behaviours\History;
use App\Models\Behaviours\Behaviour;
use Illuminate\Database\Eloquent\Model;

class HistoryControllerTest extends TestCase
{
    protected $baseRouteName = 'days.histories';
    protected $baseViewPath = 'day';
    protected $className = History::class;

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
    public function a_user_can_get_a_collection_of_models()
    {
        $models = $this->className::factory()->times(3)->create([

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

        $day = Day::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $behaviour = Behaviour::factory()->create([
            'user_id' => $this->user->id,

        ]);

        $data = [
            'behaviour_id' => $behaviour->id,
            'ordinal' => 1,
        ];

        $this->postJson(route($this->baseRouteName . '.store', ['day' => $day->id]), $data)
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
        $this->withoutExceptionHandling();

        $model = $this->createModel();

        $this->deleteModel($model, ['day' => $model->id])
            ->assertRedirect();
    }

    protected function createModel(): Model
    {
        return $this->className::factory()->create([
            //
        ]);
    }
}
