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
        $model = factory(Time::class)->create();
    }

    /**
     * @test
     */
    public function it_can_encode_the_csv()
    {
        $csv = "6845";"8";"2020-01-07 03:45:01";"0000-00-00 00:00:00";"0.00";"0";"0";"0000-00-00 00:00:00";"0000-00-00 00:00:00";"0000-00-00 00:00:00\n";
        $base64encodedCsv = base64_encode($csv);

        dump($base64encodedCsv);

        $this->assertEquals('Njg0NQ==', $base64encodedCsv);
    }

    /**
     * @test
     */
    public function it_can_import_times()
    {
        $this->assertCount(0, Time::all());

        $time = factory(Time::class)->make();

        $data = [
            0 =>$time->foreign_id,
            1 => $time->start_at->format('Y-m-d H:i:s'),
            2 => $time->end_at->format('Y-m-d H:i:s'),
            3 => '',
            4 => 0,
            5 => '',
            6 => '',
            7 => '',
            8 => '',
            9 => '0000-00-00 00:00:00',
        ];

        $time = Time::createFromCsv(1, $time->month, $data);

        $this->assertEquals(1, Time::count());
    }

    /**
     * @test
     */
    public function it_does_not_import_deleted_times()
    {
        $this->assertCount(0, Time::all());

        $time = factory(Time::class)->make();

        $data = [
            0 =>$time->foreign_id,
            1 => $time->start_at->format('Y-m-d H:i:s'),
            2 => $time->end_at->format('Y-m-d H:i:s'),
            3 => '',
            4 => 0,
            5 => '',
            6 => '',
            7 => '',
            8 => '',
            9 => now()->format('Y-m-d H:i:s'),
        ];

        $time = Time::createFromCsv(1, $time->month, $data);

        $this->assertEquals(0, Time::count());
    }

    /**
     * @test
     */
    public function it_deletes_deleted_times_on_import()
    {
        $this->assertEquals(0, Time::count());

        $time = factory(Time::class)->create();

        $this->assertEquals(1, Time::count());

        $data = [
            0 =>$time->foreign_id,
            1 => $time->start_at->format('Y-m-d H:i:s'),
            2 => $time->end_at->format('Y-m-d H:i:s'),
            3 => '',
            4 => 0,
            5 => '',
            6 => '',
            7 => '',
            8 => '',
            9 => now()->format('Y-m-d H:i:s'),
        ];

        $time = Time::createFromCsv($time->user_id, $time->month, $data);

        $this->assertEquals(0, Time::count());
    }
}
