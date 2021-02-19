<?php

namespace App\Http\Livewire\Lifeareas\Levels\Goals;

use App\Models\Lifeareas\Lifearea;
use App\Models\Lifeareas\Scale;
use App\Models\Services\Data\Attributes\Attribute;
use App\Models\Services\Data\Attributes\Groups\Group;
use Livewire\Component;

class Index extends Component
{
    public $lifearea;
    public $model;
    public $items;

    protected $listeners = [
        'deleted' => 'setItems',
    ];

    public $form = [
        'attribute_id' => 0,
    ];

    public function mount(Lifearea $lifearea, Scale $model)
    {
        $this->lifearea = $lifearea;
        $this->model = $model;
        $this->setItems();
    }

    public function create()
    {
        $goal = $this->model->goals()->create([
            'user_id' => auth()->user()->id,
            'lifearea_id' => $this->lifearea->id,
            'data_attribute_id' => $this->form['attribute_id'],
        ]);

        // $this->items[] = $goal;

        $this->setItems();
    }

    public function setItems()
    {
        $this->items = $this->model->goals()
            ->orderBy('is_completed', 'ASC')
            ->get();
    }

    public function render()
    {
        $attribute_groups = Group::with([
            'attributes',
        ])
            ->orderBy('name', 'ASC')
            ->get();

        return view('livewire.lifeareas.levels.goals.index')
            ->with('attribute_groups', $attribute_groups);
    }
}
