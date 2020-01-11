<?php

namespace Tests\Unit\Models\Work;

use App\Models\Work\Month;
use App\Models\Work\Time;
use Tests\TestCase;

class MonthTest extends TestCase
{
    const WORKINGDAYS_JANUARY_2020 = 22;

    /**
     * @test
     */
    public function it_can_set_its_available_working_days()
    {
        $model = factory(Month::class)->create([
            'date' => '2020-01-01',
        ]);

        $this->assertEquals(self::WORKINGDAYS_JANUARY_2020, $model->available_working_days);
    }

    /**
     * @test
     */
    public function it_can_be_cached()
    {
        $model = factory(Month::class)->create([
            'date' => '2020-01-01',
        ]);

        $times = factory(Time::class, 3)->create([
            'user_id' => $model->user_id,
            'month_id' => $model->id,
        ]);

        $this->assertEquals(0, $model->days_worked);
        $this->assertEquals(0, $model->hours_worked);

        $model->cache();

        $this->greaterThan(0, $model->days_worked);
        $this->assertEquals(3 * 9, $model->hours_worked);
    }

    /**
     * @test
     */
    public function it_can_be_cached_without_times()
    {
        $model = factory(Month::class)->create([
            'date' => '2020-01-01',
        ]);

        $this->assertEquals(0, $model->days_worked);
        $this->assertEquals(0, $model->hours_worked);

        $model->cache();

        $this->greaterThan(0, $model->days_worked);
        $this->assertEquals(0, $model->hours_worked);
    }
}
