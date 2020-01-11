<?php

namespace Tests\Unit\Models\Work;

use App\Models\Work\Time;
use Tests\TestCase;

class TimeTest extends TestCase
{
    /**
     * @test
     */
    public function it_calculates_its_duration()
    {
        dump(Time::class);
        $model = factory(Time::class)->create();
    }
}
