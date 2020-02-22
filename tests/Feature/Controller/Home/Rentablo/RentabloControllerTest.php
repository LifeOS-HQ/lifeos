<?php

namespace Tests\Feature\Controller\Home\Rentablo;

use DummyFullModelClass;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class RentabloControllerTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_data_for_the_home_widget()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $response = $this->getJson('home/rentablo')
            ->assertStatus(Response::HTTP_OK);
    }
}
