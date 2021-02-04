<?php

namespace App\Http\Livewire\Workouts\Histories;

use App\Models\Workouts\History;
use App\Models\Workouts\Workout;
use Livewire\Component;

class Show extends Component
{
    public $workout_history;
    public $workout;
    public $exercise_histories = [];
    public $next_set;

    public function mount(History $workout_history, Workout $workout)
    {
        $this->workout_history = $workout_history;
        $this->workout = $workout;
    }

    public function setExerciseHistories()
    {
        $this->exercise_histories = $this->workout_history->exercise_histories()->with([
            'exercise',
            'sets',
        ])->latest()->get();
        // $this->nextSet();
    }

    public function nextSet()
    {
        $last_set_history = $this->exercise_histories->last();
        $last_order = $this->workout->exercises->where('id', $last_set_history->exercise_id)->first()->pivot->order;
        $next_order = (($last_order == $this->workout->exercises->count()) ? 1 : $last_order + 1);
        $next_exercise = $this->workout->exercises->where('pivot.order', $next_order)->first();
        $next_exercise->reps_count = $this->workout_history->sets()->where('exercise_id', $next_exercise->id)->sum('reps_count');
        $exercise_id = $next_exercise->id;

        $set_history = $this->workout_history->sets()->create([
            'user_id' => $this->workout_history->user_id,
            'exercise_id' => $exercise_id,
        ]);

        $set_history->exercise = $next_exercise;
        $this->exercise_histories->push($set_history);
        $this->next_set = $set_history;
    }

    public function render()
    {
        return view('livewire.workouts.histories.show');
    }
}
