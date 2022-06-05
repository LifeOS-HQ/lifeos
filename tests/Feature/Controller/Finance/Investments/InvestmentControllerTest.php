<?php

namespace Tests\Feature\Controller\Finance\Investments;

use Tests\TestCase;
use DummyFullModelClass;
use Illuminate\Http\Response;
use App\Models\Services\Service;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvestmentControllerTest extends TestCase
{
    protected function setUp() : void
    {
        parent::setUp();

        $service = Service::updateOrCreate([
            'slug' => 'rentablo',
            'name' => 'Rentablo',
            'type' => 'password',
        ]);

        $user = $this->signIn();

        \App\Models\Services\User::create([
            'user_id' => auth()->user()->id,
            'service_id' => $service->id,
            'service_user_id' => $user->id,
            'username' => 'daniel@hof-sundermeier.de',
            'password' => '12345678',
        ]);
    }

    /**
     * @test
     */
    public function it_can_create_add_lots()
    {
        $this->withoutExceptionHandling();

        $response = $this->post(route('finance.investments.store'), [
            'account_id' => 917,
            'investment_id' => 1544,
            'date_formated' => now()->format('d.m.Y'),
            'security_price_formated' => '20,00',
            'number_of_lots_formated' => '10,00',
            'commission_formated' => '7,50',
        ]);

        $data = $response->decodeResponseJson();

        dump($data);

        $this->assertFalse($data['hasValidationErrors']);
        $this->assertCount(0, $data['transactionValidationErrors']);
    }
}
