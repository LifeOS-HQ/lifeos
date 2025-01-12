<?php

namespace Tests\Feature\Controller\Services;

use App\Models\Behaviours\History;
use Tests\TestCase;
use App\Models\Services\User;
use Illuminate\Http\Response;
use App\Models\Services\Service;
use Illuminate\Support\Carbon;

class WebhookControllerTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_update_or_create_behaviour_histories_via_webhook()
    {
        $date = Carbon::parse('2021-01-01 00:00:00', 'UTC');

        $service = factory(Service::class)->create([
            'slug' => 'habitica',
            'type' => 'password',
        ]);

        $service_user = User::factory()->create([
            'service_id' => $service->id,
            'service_user_id' => '1234',
        ]);

        $payload = [
            'type' => 'scored',
            'user' => [
                '_id' => '1234',
            ],
            'task' => [
                'id' => '1234',
                'text' => 'Test Task',
                'history' => [
                    [
                        'date' => $date->timestamp * 1000,
                        'completed' => true,
                    ],
                ],
            ],
        ];

        $this->post(route('habitica.webhook.store'), $payload)
            ->assertStatus(Response::HTTP_OK);

        $history = History::query()
            ->with([
                'day',
            ])
            ->first();

        $this->assertEquals($date, $history->start_at);
        $this->assertEquals($date, $history->end_at);
        $this->assertEquals($date->format('Y-m-d'), $history->day->date->format('Y-m-d'));
    }
}
