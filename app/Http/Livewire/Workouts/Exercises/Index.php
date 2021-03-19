<?php

namespace App\Http\Livewire\Workouts\Exercises;

use App\Models\Workouts\Set;
use Livewire\Component;

class Index extends Component
{
    public $model;
    public $form = [
        'exercise_id' => 0,
    ];

    public function mount($model)
    {
        $this->model = $model;
    }

    public function create()
    {
        if ($this->form['exercise_id'] == 0) {
            return;
        }

        $this->model->exercises()->attach($this->form['exercise_id'], [
            'user_id' => $this->model->user_id,
            'goal_type' => 'reps_count',
            'goal_target' => 1,
            'order' => ($this->model->exercises()->count() + 1),
        ]);

        $this->form['exercise_id'] = 0;
    }

    public function destroy(int $id)
    {
        Set::where('exercise_id', $id)
            ->where('workout_id', $this->model->id)
            ->delete();
        $this->model->exercises()->detach($id);
    }

    public function getItems()
    {
        return $this->model->exercises()
            ->with([
                'exercise',
            ])
            ->get();
    }

    public function render()
    {
        $items = $this->getItems();

        return view('livewire.workouts.exercises.index')
            ->with('exercises', auth()->user()->exercises()->whereNotIn('id', $items->pluck('exercise.id'))->orderBy('name')->get())
            ->with('items', $items);
    }
}
