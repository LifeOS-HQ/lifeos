<?php

namespace Tests\Unit\Diet\Diary;

use Tests\TestCase;
use App\Models\Diet\Diary\Day;
use Illuminate\Support\Carbon;
use App\Models\Diet\Foods\Food;

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

    /**
     * @test
     */
    public function it_can_populate_meals_from_last_day()
    {
        $foods = Food::factory()->count(3)->create([]);

        $yesterday = Carbon::parse('2021-10-10 12:00:00');
        $this->travelTo($yesterday);

        $last_day = Day::factory()->create([
            'user_id' => $this->user->id,
            'at' => $yesterday,
        ]);
        $last_day_meal = $last_day->meals()->create([
            'rating_comments' => null,
            'order_by' => 0,
            'user_id' => $last_day->user_id,
        ]);

        foreach ($foods as $food) {
            $last_day_meal->foods()->create([
                'amount' => 100,
                'food_id' => $food->id,
                'user_id' => $last_day->user_id,
            ]);
        }

        $today = $yesterday->copy()->addDay();
        $this->travelTo($today);

        $day = Day::factory()->create([
            'user_id' => $this->user->id,
            'at' => $today,
        ]);

        $this->assertCount(0, $day->meals);

        $day->populateMealsFromLastDay();

        $day->refresh();

        $this->assertCount(1, $day->meals);

        foreach ($day->meals as $meal) {
            $this->assertEquals($today->format('Y-m-d'), $meal->at->format('Y-m-d'));
            $this->assertEquals($last_day_meal->name, $meal->name);
            $this->assertEquals($last_day_meal->order_by, $meal->order_by);
            $this->assertEquals($last_day_meal->user_id, $meal->user_id);

            $this->assertCount(3, $meal->foods);
            foreach ($meal->foods as $index => $meal_food) {
                $this->assertEquals(100, $meal_food->amount);
                $this->assertEquals($foods[$index]->id, $meal_food->food_id);
                $this->assertEquals($last_day->user_id, $meal_food->user_id);
            }
        }
    }

    /**
     * @test
     */
    public function it_can_populate_meals_from_last_weekday()
    {
        $this->markTestSkipped('sqlite does not support WEEKDAY function');

        $foods = Food::factory()->count(3)->create([]);

        $last_week = Carbon::parse('2021-10-10 12:00:00');
        $this->travelTo($last_week);

        $last_day = Day::factory()->create([
            'user_id' => $this->user->id,
            'at' => $last_week,
        ]);
        $last_day_meal = $last_day->meals()->create([
            'rating_comments' => null,
            'order_by' => 0,
            'user_id' => $last_day->user_id,
        ]);

        foreach ($foods as $food) {
            $last_day_meal->foods()->create([
                'amount' => 100,
                'food_id' => $food->id,
                'user_id' => $last_day->user_id,
            ]);
        }

        $six_days_ago = $last_week->copy()->addDay();
        $this->travelTo($six_days_ago);

        $day = Day::factory()->create([
            'user_id' => $this->user->id,
            'at' => $six_days_ago,
        ]);

        $this->assertCount(0, $day->meals);

        $day->populateMealsFromLastWeekday();

        $day->refresh();

        $this->assertCount(1, $day->meals);

        $today = $last_week->copy()->addWeek();
        $this->travelTo($today);

        $day = Day::factory()->create([
            'user_id' => $this->user->id,
            'at' => $today,
        ]);

        $this->assertCount(0, $day->meals);

        $day->populateMealsFromLastWeekday();

        $day->refresh();

        $this->assertCount(1, $day->meals);

        foreach ($day->meals as $meal) {
            $this->assertEquals($today->format('Y-m-d'), $meal->at->format('Y-m-d'));
            $this->assertEquals($last_day_meal->name, $meal->name);
            $this->assertEquals($last_day_meal->order_by, $meal->order_by);
            $this->assertEquals($last_day_meal->user_id, $meal->user_id);

            $this->assertCount(3, $meal->foods);
            foreach ($meal->foods as $index => $meal_food) {
                $this->assertEquals(100, $meal_food->amount);
                $this->assertEquals($foods[$index]->id, $meal_food->food_id);
                $this->assertEquals($last_day->user_id, $meal_food->user_id);
            }
        }
    }
}
