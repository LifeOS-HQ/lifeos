<?php

namespace Tests\Unit\Diet\Diary;

use Tests\TestCase;
use App\Models\Diet\Diary\Day;

class DayTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_format_its_at_attribute()
    {
        $at = now();

        $day = Day::factory()->create([
            'at' => $at,
        ]);

        $this->assertEquals($at->format('d.m.Y'), $day->at_formatted);
    }
}
