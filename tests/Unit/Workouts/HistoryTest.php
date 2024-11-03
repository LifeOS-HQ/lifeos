<?php

namespace Tests\Unit\Workouts;

use Tests\TestCase;
use App\Models\Workouts\History;
use App\Models\Workouts\Workout;

class HistoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_updated_or_created_by_wahoo()
    {
        $wahoo_workout_id = 323531543;
        $wahoo_workout = json_decode(file_get_contents(base_path('tests/snapshots/wahoo/workouts/' . $wahoo_workout_id . '.json')), true);

        $workout = Workout::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $history = History::updateOrCreateFromWahoo($workout, $wahoo_workout);

        $this->assertCount(1, History::all());

        $this->assertEquals($workout->id, $history->workout_id);
        $this->assertEquals($this->user->id, $history->user_id);
        $this->assertEquals('wahoo', $history->source_slug);
        $this->assertEquals($wahoo_workout['id'], $history->source_id);

        $this->assertEquals('2024-11-02T04:21:10.000Z', $history->start_at->format('Y-m-d\TH:i:s.v\Z'));
        $this->assertEquals('2024-11-02T05:27:14.000Z', $history->end_at->format('Y-m-d\TH:i:s.v\Z'));

        $this->assertEquals($wahoo_workout['workout_summary']['ascent_accum'], $history->ascent_accum);
        $this->assertEquals($wahoo_workout['workout_summary']['calories_accum'], $history->calories_accum);
        $this->assertEquals($wahoo_workout['workout_summary']['cadence_avg'], $history->cadence_avg);
        $this->assertEquals($wahoo_workout['workout_summary']['distance_accum'], $history->distance_accum);
        $this->assertEquals($wahoo_workout['workout_summary']['duration_active_accum'], $history->duration_active_accum);
        $this->assertEquals($wahoo_workout['workout_summary']['duration_paused_accum'], $history->duration_paused_accum);
        $this->assertEquals($wahoo_workout['workout_summary']['duration_total_accum'], $history->duration_total_accum);
        $this->assertEquals($wahoo_workout['workout_summary']['heart_rate_avg'], $history->heart_rate_avg);
        $this->assertEquals($wahoo_workout['workout_summary']['power_avg'], $history->power_avg);
        $this->assertEquals(0, $history->power_bike_np_last);
        $this->assertEquals(0, $history->power_bike_tss_last);
        $this->assertEquals($wahoo_workout['workout_summary']['speed_avg'], $history->speed_avg);
        $this->assertEquals($wahoo_workout['workout_summary']['work_accum'], $history->work_accum);

        $history = History::updateOrCreateFromWahoo($workout, $wahoo_workout);

        $this->assertCount(1, History::all());
    }
}
