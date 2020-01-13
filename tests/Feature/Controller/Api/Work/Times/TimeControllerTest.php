<?php

namespace Tests\Feature\Controller\Api\Work\Times;

use App\Models\Work\Time;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TimeControllerTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_store_models()
    {
        $this->withoutExceptionHandling();

        $filename = 'betriko_arbeitszeit.csv';

        $base64encodedCsv = base64_encode(Storage::get($filename));

        $this->assertCount(0, Time::all());

        $response = $this->post('api/work/time', [
            'api_token' => $this->user->api_token,
            'csv' => $base64encodedCsv,
        ]);

        $this->greaterThan(0, Time::count());
    }
}
