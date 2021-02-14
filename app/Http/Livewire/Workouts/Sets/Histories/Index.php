<?php

namespace App\Http\Livewire\Workouts\Sets\Histories;

use App\Models\Exercises\Exercise;
use App\Models\Workouts\Workout;
use Livewire\Component;

class Index extends Component
{
    public $exercise_history;
    public $workout_history;
    public $items = [];
    public $form = [];

    public function mount(\App\Models\Workouts\Exercises\History $exercise_history, \App\Models\Workouts\History $workout_history)
    {
        $this->exercise_history = $exercise_history;
        $this->workout_history = $workout_history;
    }

    public function create()
    {
        $items_count = $this->items->count();

        $attributes = [
            'user_id' => $this->exercise_history->user_id,
            'workout_history_id' => $this->workout_history->id,
            'order' => ($items_count + 1),
        ];

        if ($items_count) {
            $last_item = $this->items->last();
            $attributes['reps_count'] = $last_item->reps_count;
            $attributes['weight_in_g'] = $last_item->weight_in_g;
        }
        else {
            $attributes['reps_count'] = 1;
            $attributes['weight_in_g'] = 0;
        }

        $this->exercise_history->sets()->create($attributes);
    }

    public function destroy(int $id)
    {
        $this->exercise_history->sets()->where('id', $id)->delete();
    }

    public function update(int $id, int $index)
    {
        $this->items[$index]->update($this->form[$id] + [
            'is_completed' => true,
        ]);
    }

    public function getItems()
    {
        return $this->exercise_history->sets()->orderBy('order')
            ->get();
    }

    public function render()
    {
        $this->items = $this->getItems();
        foreach ($this->items as $key => $item) {
            $this->form[$item->id] = [
                'reps_count' => $item->reps_count,
                'weight_in_g' => $item->weight_in_g,
            ];
        }

        return view('livewire.workouts.sets.histories.index')
            ->with('items', $this->items);
    }
}
