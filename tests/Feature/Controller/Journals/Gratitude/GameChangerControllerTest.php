<?php

namespace Tests\Feature\Controller\Journals\Gratitude;

use App\Models\Journals\Gratitude\Gratitude;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class GameChangerControllerTest extends TestCase
{
    protected $baseRouteName = 'journal.gratitude.gamechanger';
    protected $baseViewPath = 'gratitude';
    protected $className = Gratitude::class;

    /**
     * @test
     */
    public function guests_can_not_access_the_following_routes()
    {
        $model = factory($this->className)->create();

        $actions = [
            'update' => ['journal' => $model->journal_id, 'gratitude' => $model->id],
        ];
        $this->guestsCanNotAccess($actions);
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

        $response = $this->put(route($this->baseRouteName . '.update', ['journal' => $model->journal_id, 'gratitude' => $model->id]), $data)
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas($model->getTable(), [
            'id' => $model->id,
            'is_game_changer' => true,
        ]);

        $response = $this->put(route($this->baseRouteName . '.update', ['journal' => $model->journal_id, 'gratitude' => $model->id]), $data)
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas($model->getTable(), [
            'id' => $model->id,
            'is_game_changer' => false,
        ]);
    }
}
