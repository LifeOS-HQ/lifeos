<?php

namespace Tests\Feature\Controller\Obstacles;

use Tests\TestCase;
use App\Models\Days\Day;
use Illuminate\Http\Response;
use App\Models\Obstacles\Obstacle;
use Illuminate\Database\Eloquent\Model;

class ObstacleControllerTest extends TestCase
{
    protected $baseRouteName = 'obstacles';
    protected $baseViewPath = 'obstacle';
    protected $className = Obstacle::class;

    /**
     * @test
     */
    public function guests_can_not_access_the_following_routes()
    {
        $model = $this->className::factory()->create();

        $actions = [
            'index' => [],
            'store' => [],
            'show' => ['obstacle' => $model->id],
            'edit' => ['obstacle' => $model->id],
            'update' => ['obstacle' => $model->id],
            'destroy' => ['obstacle' => $model->id],
        ];
        $this->guestsCanNotAccess($actions);
    }

    /**
     * @test
     */
    public function a_user_can_not_see_things_from_a_different_user()
    {
        $modelOfADifferentUser = $this->className::factory()->create();

        $this->a_user_can_not_see_models_from_a_different_user(['obstacle' => $modelOfADifferentUser->id]);
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
    public function a_user_can_get_collection_of_models()
    {
        $models = $this->className::factory()->times(3)->create([
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

        $day = Day::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $data = [
            'challenge' => 'challenge1',
            'level' => 2,
            'loot' => 'loot1',
            'obstacle' => 'obstacle1',
            'outcome' => 'outcome1',
            'plan' => 'plan1',
            'wish' => 'wish1',
        ];

        $this->postJson(route($this->baseRouteName . '.store'), $data)
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas((new $this->className)->getTable(), $data + [
            'user_id' => $this->user->id
        ]);
    }

    /**
     * @test
     */
    public function a_user_can_see_the_show_view()
    {
        $this->withoutExceptionHandling();

        $model = $this->createModel();

        $this->getShowViewResponse(['obstacle' => $model->id])
            ->assertViewIs($this->baseViewPath . '.show')
            ->assertViewHas('model');
    }

    /**
     * @test
     */
    public function a_user_can_see_the_edit_view()
    {
        $model = $this->createModel();

        $this->getEditViewResponse(['obstacle' => $model->id])
            ->assertViewIs($this->baseViewPath . '.edit')
            ->assertViewHas('model');
    }

    /**
     * @test
     */
    public function a_user_can_update_a_model()
    {
        $this->withoutExceptionHandling();

        $model = $this->createModel([
            'challenge' => 'challenge',
            'level' => 1,
            'title' => 'title',
            'wish' => 'wish',
            'outcome' => 'outcome',
            'obstacle' => 'obstacle',
            'plan' => 'plan',
            'loot' => 'loot',
            'is_active' => true,
        ]);

        $this->signIn();

        $data = [
            'challenge' => 'challenge1',
            'level' => 2,
            'title' => 'title1',
            'wish' => 'wish1',
            'outcome' => 'outcome1',
            'obstacle' => 'obstacle1',
            'plan' => 'plan1',
            'loot' => 'loot1',
            'is_active' => false,
        ];

        $response = $this->put(route($this->baseRouteName . '.update', ['obstacle' => $model->id]), $data)
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

        $this->deleteModel($model, ['obstacle' => $model->id])
            ->assertRedirect();
    }

    protected function createModel(): Model
    {
        return $this->className::factory()->create([
            'user_id' => $this->user->id,
        ]);
    }
}
