<?php

namespace Tests\Unit\Apis\Rentablo;

use App\Apis\Rentablo\Rentablo;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class RentabloTest extends TestCase
{
    protected $api;

    protected function setUp() : void
    {
        parent::setUp();

        $service = \App\Models\Services\Service::create([
            'slug' => 'rentablo',
            'name' => 'Rentablo',
            'type' => 'password',
        ]);

        $this->signIn();

        \App\Models\Services\User::create([
            'user_id' => $this->user->id,
            'service_user_id' => $this->user->id,
            'service_id' => $service->id,
            'username' => config('services.rentablo.username'),
            'password' => config('services.rentablo.password'),
        ]);

        $this->api = App::make('RentabloApi');
    }

    /**
     * @test
     */
    public function it_can_be_build_from_the_service_container()
    {
        $this->assertInstanceOf(Rentablo::class, $this->api);
        $this->assertTrue(Cache::has('services.rentablo'));
    }

    /**
     * @test
     */
    public function it_can_get_data_for_the_home_view()
    {

        $data = $this->api->home();
        $this->assertArrayHasKey('dividends', $data);
        $this->assertArrayHasKey('valuations', $data);
        $this->assertTrue(Cache::has('home.rentablo'));
    }

    /**
     * @test
     */
    public function it_can_get_data_for_the_portfolio_index_view()
    {
        $data = $this->api->years();
        // dump($data);
        $this->assertIsArray($data);
        $this->assertTrue(Cache::has('rentablo.years'));
    }

    /**
     * @test
     */
    public function it_gets_the_dividend_per_month_and_investment()
    {
        $data = $this->api->dividendsPerMonthDataAndInvestmentData(2020);
        // dump($data);
        $this->assertArrayHasKey('dividends', $data);
    }

    /**
     * @test
     */
    public function it_gets_the_investments()
    {
        $data = $this->api->getInvestments();
        // dump($data);
        $this->assertArrayHasKey('investments', $data);
    }
}
