<?php

namespace Tests\Feature\Controller\Work;

use App\Models\Work\Month;
use App\Models\Work\Year;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class MonthControllerTest extends TestCase
{
    protected $baseRouteName = 'month';
    protected $baseViewPath = 'month';
    protected $className = Month::class;

    /**
     * @test
     */
    public function guests_can_not_access_the_following_routes()
    {
        $id = factory($this->className)->create()->id;

        $actions = [
            'index' => [],
            'store' => [],
            'show' => ['month' => $id],
            'edit' => ['month' => $id],
            'update' => ['month' => $id],
            'destroy' => ['month' => $id],
        ];
        $this->guestsCanNotAccess($actions);
    }

    /**
     * @test
     */
    public function a_user_can_not_see_things_from_a_different_user()
    {
        $modelOfADifferentUser = factory($this->className)->create();

        $this->a_user_can_not_see_models_from_a_different_user(['month' => $modelOfADifferentUser->id]);
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
        $year = factory(Year::class)->create([
            'user_id' => $this->user->id,
        ]);

        $models = factory($this->className, 3)->create([
            'user_id' => $this->user->id,
            'year_id' => $year->id,
        ]);

        $this->getCollection();
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
            'bonus_formatted' => '10000,00',
            'net_formatted' => '3000,00',
        ];

        $response = $this->put(route($this->baseRouteName . '.update', ['month' => $model->id]), $data)
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas($model->getTable(), [
            'id' => $model->id,
            'bonus_in_cents' => 1000000,
            'net_in_cents' => 300000
        ]);
    }
}
