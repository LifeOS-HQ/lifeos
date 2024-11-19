<?php

namespace Tests\Feature\Controller\Services;

use Tests\TestCase;
use App\Models\Services\User;
use Illuminate\Http\Response;
use App\Models\Services\Service;
use Illuminate\Support\Facades\Http;

class UserControllerTest extends TestCase
{
    protected $baseRouteName = 'user.services';
    protected $baseViewPath = 'DummyModelVariable';
    protected $className = User::class;

    /**
     * @test
     */
    public function a_user_can_create_a_model_from_habitica()
    {
        $habitica_response_json = file_get_contents(base_path('tests/snapshots/habitica/login/success.json'));
        $habitica_response_data = json_decode($habitica_response_json, true);

        Http::fake([
            'habitica.com/api/v3/user/auth/local/login' => Http::response($habitica_response_json, Response::HTTP_OK),
        ]);

        $this->signIn();

        $service = factory(Service::class)->create([
            'slug' => 'habitica',
            'name' => 'Habitica',
            'type' => 'password',
        ]);

        $data = [
            'username' => 'username',
            'password' => 'password',
        ];

        $response = $this->post(route($this->baseRouteName . '.store', ['service' => $service->id]), $data)
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasNoErrors();

        $service_user = User::where('user_id', auth()->id())
            ->where('service_id', $service->id)
            ->first();

        $this->assertEquals($habitica_response_data['data']['username'], $service_user->username);
        $this->assertEquals($habitica_response_data['data']['id'], $service_user->service_user_id);
        $this->assertEquals($habitica_response_data['data']['apiToken'], $service_user->token);
    }

    /**
     * @test
     */
    public function guests_can_not_access_the_following_routes()
    {
        $id = factory($this->className)->create()->id;

        $actions = [
            'index' => [],
            'store' => [],
            'show' => ['DummyModelVariable' => $id],
            'edit' => ['DummyModelVariable' => $id],
            'update' => ['DummyModelVariable' => $id],
            'destroy' => ['DummyModelVariable' => $id],
        ];
        $this->guestsCanNotAccess($actions);
    }

    /**
     * @test
     */
    public function a_user_can_not_see_things_from_a_different_user()
    {
        $modelOfADifferentUser = factory($this->className)->create();

        $this->a_user_can_not_see_models_from_a_different_user(['DummyModelVariable' => $modelOfADifferentUser->id]);
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
    public function a_user_can_get_a_paginated_collection_of_models()
    {
        $models = factory($this->className, 3)->create([

        ]);

        $this->getPaginatedCollection();
    }

    /**
     * @test
     */
    public function a_user_can_create_a_model()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $data = [

        ];

        $this->post(route($this->baseRouteName . '.store'), $data)
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

        $this->getShowViewResponse(['DummyModelVariable' => $model->id])
            ->assertViewIs($this->baseViewPath . '.show')
            ->assertViewHas('model');
    }

    /**
     * @test
     */
    public function a_user_can_see_the_edit_view()
    {
        $model = $this->createModel();

        $this->getEditViewResponse(['DummyModelVariable' => $model->id])
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

        $response = $this->put(route($this->baseRouteName . '.update', ['DummyModelVariable' => $model->id]), $data)
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

        $this->deleteModel($model, ['DummyModelVariable' => $model->id])
            ->assertRedirect();
    }
}
