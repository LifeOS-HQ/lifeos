<?php

namespace Tests\Unit\Models\Work;

use App\Models\Work\Month;
use App\Models\Work\Time;
use App\Models\Work\Year;
use Tests\TestCase;

class YearTest extends TestCase
{
    const WORKINGDAYS_2020 = 254;

    /**
     * @test
     */
    public function it_can_get_its_holidays()
    {
        $model = factory(Year::class)->create();
        $holidays = $model->fetchHolidays();

        $this->assertIsArray($holidays);
    }

    /**
     * @test
     */
    public function it_get_the_planned_working_hours_day()
    {
        $model = factory(Year::class)->create([
            'date' => '2020-01-01',
        ]);

        $model->planned_working_hours = 1000;
        $model->available_working_days = 100;

        $this->assertEquals(10, $model->planned_working_hours_day);

        $this->assertEquals('10,00', $model->planned_working_hours_day_formatted);
    }

    /**
     * @test
     */
    public function it_can_set_its_available_working_days()
    {
        $model = factory(Year::class)->create([
            'date' => '2020-01-01',
        ]);

        $this->assertEquals(self::WORKINGDAYS_2020, $model->available_working_days);
    }

    /**
     * @test
     */
    public function it_can_be_cached()
    {
        $model = factory(Year::class)->create([
            'date' => '2020-01-01',
        ]);

        for ($i=1; $i <= 12; $i++) {
            $month = factory(Month::class)->create([
                'user_id' => $model->user_id,
                'year_id' => $model->id,
                'date' => '2020-' . $i . '-01',
            ]);

            $times = factory(Time::class, 3)->create([
                'user_id' => $model->user_id,
                'month_id' => $month->id,
            ]);

            // $month->cache()
            //     ->save();
        }

        $model->cacheMonths();

        $this->assertEquals(0, $model->days_worked);
        $this->assertEquals(0, $model->hours_worked);

        $model->cache();

        $this->greaterThan(0, $model->days_worked);
        $this->assertEquals(3 * 9 * 12, $model->hours_worked);
    }
}
