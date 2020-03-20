<?php

namespace Tests\Feature\Controller\Home\Servers;

use DummyFullModelClass;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class StatusControllerTest extends TestCase
{
    /**
     * @test
     */
    public function it_gets_the_status_of_the_server()
    {
        $this->signIn();

        $response = $this->get(route('home.server.index'))
            ->assertStatus(Response::HTTP_OK);
            $json = $response->json();

        $this->assertArrayHasKey('uptime', $json);
    }
}
