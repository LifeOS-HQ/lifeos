<?php

namespace App\Http\Livewire\Workouts;

use Livewire\Component;

class Index extends Component
{
    public $form = [
        'name' => ''
    ];

    public $rules = [
        'form.name' => 'required|string',
    ];

    public function create()
    {
        $attributes = $this->validate();
        $model = auth()->user()->workouts()->create($this->form);
    }

    public function getItems()
    {
        return auth()->user()
            ->workouts()
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.workouts.index')
            ->with('items', $this->getItems());
    }
}
