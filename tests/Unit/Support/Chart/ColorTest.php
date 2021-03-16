<?php

namespace Tests\Unit\Support\Chart;

use App\Support\Chart\Color;
use PHPUnit\Framework\TestCase;

class ColorTest extends TestCase
{
    /**
     * @test
     */
    public function it_gets_a_color()
    {
        $this->assertEquals('#7cb5ec', Color::get(0));
        $this->assertEquals('#7cb5ec', Color::get(10));

        $this->assertEquals('#f15c80', Color::get(5));
        $this->assertEquals('#f15c80', Color::get(15));
        $this->assertEquals('#8085e9', Color::get(16));
        $this->assertEquals('#f15c80', Color::get(105));
    }
}
