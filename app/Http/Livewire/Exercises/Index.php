<?php

namespace App\Http\Livewire\Exercises;

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
        $model = auth()->user()->exercises()->create($this->form);
    }

    public function getItems()
    {
        return auth()->user()
            ->exercises()
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.exercises.index')
            ->with('items', $this->getItems());
    }
}
