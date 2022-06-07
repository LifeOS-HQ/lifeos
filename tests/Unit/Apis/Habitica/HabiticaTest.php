<?php

namespace Tests\Unit\Apis\Habitica;

use Tests\TestCase;
use App\Apis\Habitica\Habitica;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class HabiticaTest extends TestCase
{
    protected $api;

    protected function setUp() : void
    {
        parent::setUp();

        $service = \App\Models\Services\Service::create([
            'slug' => 'habitica',
            'name' => 'Habitica',
            'type' => 'password',
        ]);

        $this->signIn();

        \App\Models\Services\User::create([
            'user_id' => $this->user->id,
            'service_user_id' => $this->user->id,
            'service_id' => $service->id,
            'username' => config('services.habitica.username'),
            'password' => config('services.habitica.password'),
        ]);

        $this->api = App::make('HabiticaApi');
    }

    /**
     * @test
     */
    public function it_can_be_build_from_the_service_container()
    {
        $this->assertInstanceOf(Habitica::class, $this->api);
        $this->assertTrue(Cache::has('services.habitica'));
    }

    /**
     * @test
     */
    public function it_can_get_the_user()
    {
        $data = $this->api->getUser();
        $this->assertTrue($data['success']);
    }

    /**
     * @test
     */
    public function is_can_use_the_skill_tools_of_trade()
    {
        $data = $this->api->cast('toolsOfTrade');
        $this->assertTrue($data['success']);
    }

    /**
     * @test
     */
    public function is_can_use_the_skill_back_stab_on_a_task()
    {
        $data = $this->api->cast('backStab', '4eba0a20-66f5-4b19-afce-b9256d595d18');
        $this->assertTrue($data['success']);
    }
}
