<?php

namespace Tests\Unit\Support\Servers;

use App\Support\Servers\Uptime;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class UptimeTest extends TestCase
{
    protected $output = ' 5:36  up 2 days,  3:04, 2 users, load averages: 3,15 2,60 2,41';

    /**
     * @test
     */
    public function it_can_parse_a_string()
    {
        $uptime = new Uptime();
        $uptime->parseOutput($this->output);
        $this->assertEquals('5:36', $uptime->time);
        $this->assertEquals(2, $uptime->uptime['value']);
        $this->assertEquals('days', $uptime->uptime['unit']);
        $this->assertEquals('2 days', $uptime->uptime['formatted']);
        $this->assertEquals(3.15, $uptime->load[1]);
        $this->assertEquals(2.60, $uptime->load[5]);
        $this->assertEquals(2.41, $uptime->load[15]);
    }

    /**
     * @test
     */
    public function it_can_be_created_from_string()
    {
        $uptime = Uptime::createFromString($this->output);
        $this->assertEquals('5:36', $uptime->time);
        $this->assertEquals(2, $uptime->uptime['value']);
        $this->assertEquals('days', $uptime->uptime['unit']);
        $this->assertEquals('2 days', $uptime->uptime['formatted']);
        $this->assertEquals(3.15, $uptime->load[1]);
        $this->assertEquals(2.60, $uptime->load[5]);
        $this->assertEquals(2.41, $uptime->load[15]);
    }

    /**
     * @test
     */
    public function it_caches_the_results()
    {
        $uptime = Uptime::get();
        $this->assertTrue(Cache::has('home.server'));
    }
}
