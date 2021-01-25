<?php

namespace App\Http\Livewire\Workouts\Sets;

use App\Models\Exercises\Exercise;
use App\Models\Workouts\Workout;
use Livewire\Component;

class Index extends Component
{
    public $exercise;
    public $workout;
    public $items = [];
    public $form = [];

    public function mount(Exercise $exercise, Workout $workout)
    {
        $this->exercise = $exercise;
        $this->workout = $workout;
    }

    public function create()
    {
        $items_count = $this->items->count();

        $attributes = [
            'user_id' => $this->exercise->user_id,
            'workout_id' => $this->workout->id,
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

        $this->exercise->sets()->create($attributes);
    }

    public function destroy(int $id)
    {
        $this->exercise->sets()->where('id', $id)->delete();
    }

    public function update(int $id, int $index)
    {
        $this->items[$index]->update($this->form[$id]);
    }

    public function getItems()
    {
        return $this->exercise->sets()->where('workout_id', $this->workout->id)->orderBy('order')
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

        return view('livewire.workouts.sets.index')
            ->with('items', $this->items);
    }
}
