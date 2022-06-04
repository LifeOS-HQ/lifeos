<?php

namespace Tests\Feature\Controller\Finance\Dividens;

use Tests\TestCase;
use DummyFullModelClass;
use Illuminate\Http\Response;
use App\Models\Services\Service;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DividendControllerTest extends TestCase
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
    public function it_can_get_the_data_for_the_widget()
    {
        $this->withoutExceptionHandling();

        $response = $this->get(route('finance.dividends.index'));
        $response->assertStatus(Response::HTTP_OK);

        $data = $response->decodeResponseJson();
        dump($data);

        $this->assertArrayHasKey('accounts', $data);

    }

    /**
     * @test
     */
    public function it_can_create_dividends()
    {
        $this->withoutExceptionHandling();

        $response = $this->post(route('finance.dividends.store'), [
            'account_id' => 917,
            'investment_id' => 1544,
            'date_formated' => now()->format('d.m.Y'),
            'security_price_formated' => '1,00',
            'tax_amount_formated' => '1,00',
        ]);

        $data = $response->decodeResponseJson();

        $this->assertFalse($data['hasValidationErrors']);
        $this->assertCount(0, $data['transactionValidationErrors']);
    }

}
