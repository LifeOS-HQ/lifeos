<?php

namespace Tests\Unit\Apis\Rentablo;

use App\Apis\Rentablo\Rentablo;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class RentabloTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_build_from_the_service_container()
    {
        $rentabloApi = App::make('RentabloApi');
        dump($rentabloApi);
        $this->assertInstanceOf(Rentablo::class, $rentabloApi);
    }

    /**
     * @test
     */
    public function it_can_get_data_for_the_home_view()
    {
        $rentabloApi = App::make('RentabloApi');
        $data = $rentabloApi->home();
        $this->assertArrayHasKey('dividends', $data);
        $this->assertArrayHasKey('valuations', $data);
        $this->assertTrue(Cache::has('home.rentablo'));
    }

    /**
     * @test
     */
    public function it_can_get_data_for_the_portfolio_index_view()
    {
        $rentabloApi = App::make('RentabloApi');
        $data = $rentabloApi->years();
        $this->assertTrue(Cache::has('rentablo.years'));
    }
}
