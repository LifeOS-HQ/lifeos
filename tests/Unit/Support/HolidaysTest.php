<?php

namespace Tests\Unit\Support;

use App\Support\Holidays;
use Carbon\Carbon;
use Tests\TestCase;

class HolidaysTest extends TestCase
{
    /**
     * @test
     */
    public function it_fetches_the_holidays_for_a_land()
    {
        $data = Holidays::fetch(2020, Holidays::LAND_NW);
        dump($data);
    }

    /**
     * @test
     */
    public function it_gets_the_cache_key()
    {
        $this->assertEquals('holidays.2020', Holidays::key(2020));
        $this->assertEquals('holidays.2020.NW', Holidays::key(2020, Holidays::LAND_NW));
    }

    /**
     * @test
     */
    public function it_gets_holidays_for_a_year()
    {
        $data = Holidays::get(2020, Holidays::LAND_NW);
        dump($data);
    }

    /**
     * @test
     */
    public function it_gets_holiday_dates_for_a_year()
    {
        $data = Holidays::dates(2020, Holidays::LAND_NW);
        dump($data);
    }

    /**
     * @test
     */
    public function it_knows_if_a_date_is_a_workingday()
    {
        $this->assertFalse(Holidays::isWorkingDay(new Carbon('2020-01-01')));
        $this->assertTrue(Holidays::isWorkingDay(new Carbon('2020-01-02')));
        $this->assertFalse(Holidays::isWorkingDay(new Carbon('2020-01-04')));
    }
}
