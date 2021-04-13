<?php

namespace Tests\Feature\Controller\Contacts;

use App\Models\Contacts\Contact;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    protected $baseRouteName = 'contacts';
    protected $baseViewPath = 'contact';
    protected $className = Contact::class;

    /**
     * @test
     */
    public function guests_can_not_access_the_following_routes()
    {
        $id = $this->className::factory()->create()->id;

        $actions = [
            'index' => [],
            'store' => [],
            'show' => ['contact' => $id],
            'edit' => ['contact' => $id],
            'update' => ['contact' => $id],
            'destroy' => ['contact' => $id],
        ];
        $this->guestsCanNotAccess($actions);
    }

    /**
     * @test
     */
    public function a_user_can_not_see_things_from_a_different_user()
    {
        $modelOfADifferentUser = $this->className::factory()->create();

        $this->a_user_can_not_see_models_from_a_different_user(['contact' => $modelOfADifferentUser->id]);
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
        $models = $this->className::factory()->count(3)->create([
            'user_id' => $this->user->id,
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

        $this->getShowViewResponse(['contact' => $model->id])
            ->assertViewIs($this->baseViewPath . '.show')
            ->assertViewHas('model');
    }

    /**
     * @test
     */
    public function a_user_can_see_the_edit_view()
    {
        $model = $this->createModel();

        $this->getEditViewResponse(['contact' => $model->id])
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

        $birthdate_at = (new Carbon('2020-02-03'))->startOfDay();
        $first_met_at = (new Carbon('2020-02-03'))->startOfDay();

        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'birthdate_at_formatted' => $birthdate_at->format('d.m.Y'),
            'email' => 'john@example.com',
            'phone_number' => '1234 56789',
            'mobile_number' => '1234 56789',
            'website' => 'https://www.example.com',
            'twitter_id' => 'twitter',
            'instagram_id' => 'instagram',
            'first_met_at_formatted' => $birthdate_at->format('d.m.Y'),
            'first_met_where' => 'school',
            'first_met_additional_info' => 'text',
            'job' => 'programmer',
            'street' => 'street 123',
            'city' => 'city',
            'postal_code' => '12345',
            'first_parent_id' => Contact::factory()->create()->id,
            'second_parent_id' => Contact::factory()->create()->id,
        ];


        $response = $this->put(route($this->baseRouteName . '.update', ['contact' => $model->id]), $data)
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasNoErrors();

        Arr::forget($data, 'birthdate_at_formatted');
        Arr::forget($data, 'first_met_at_formatted');

        $data['birthdate_at'] = $birthdate_at;
        $data['first_met_at'] = $first_met_at;

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

        $this->deleteModel($model, ['contact' => $model->id])
            ->assertRedirect();
    }

    protected function createModel() : Model
    {
        return $this->className::factory()->create([
            'user_id' => $this->user->id,
        ])->fresh();
    }
}
