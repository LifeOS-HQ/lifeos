<?php

namespace Tests\Unit\Apis\Rentablo;

use App\Apis\Rentablo\Rentablo;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class RentabloTest extends TestCase
{
    protected $rentabloApi;

    protected function setUp() : void
    {
        parent::setUp();

        $this->rentabloApi = App::make('RentabloApi');
    }

    /**
     * @test
     */
    public function it_can_be_build_from_the_service_container()
    {

        dump($this->rentabloApi);
        $this->assertInstanceOf(Rentablo::class, $this->rentabloApi);
    }

    /**
     * @test
     */
    public function it_can_get_data_for_the_home_view()
    {

        $data = $this->rentabloApi->home();
        $this->assertArrayHasKey('dividends', $data);
        $this->assertArrayHasKey('valuations', $data);
        $this->assertTrue(Cache::has('home.rentablo'));
    }

    /**
     * @test
     */
    public function it_can_get_data_for_the_portfolio_index_view()
    {
        $data = $this->rentabloApi->years();
        $this->assertTrue(Cache::has('rentablo.years'));
    }

    /**
     * @test
     */
    public function it_gets_the_dividend_per_month_and_investment()
    {
        $data = $this->rentabloApi->dividendsPerMonthDataAndInvestmentData(2020);
        dump($data);
    }
}
