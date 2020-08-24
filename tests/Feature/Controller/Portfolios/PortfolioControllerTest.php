<?php

namespace Tests\Feature\Controller\Portfolios;

use DummyFullModelClass;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class PortfolioControllerTest extends TestCase
{
    protected $baseRouteName = 'portfolio';
    protected $baseViewPath = 'portfolio';

    /**
     * @test
     */
    public function it_can_get_the_data_from_rentablo()
    {
        $this->signIn();
        $parameters = [];
        $response = $this->json('get', route($this->baseRouteName . '.index', $parameters), []);
    }
}
