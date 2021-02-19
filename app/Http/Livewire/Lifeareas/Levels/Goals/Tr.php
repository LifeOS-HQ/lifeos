<?php

namespace App\Http\Livewire\Lifeareas\Levels\Goals;

use App\Models\Lifeareas\Levels\Goals\Goal;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Tr extends Component
{
    public $attribute_groups = [];
    public $model;
    public $form = [];

    public function mount(Collection $attribute_groups, Goal $model)
    {
        $this->attribute_groups = $attribute_groups;
        $this->model = $model;
        $this->form = [
            'data_attribute_id' => $model->data_attribute_id,
            'start_formatted' => number_format($model->start, 6, ',', '.'),
            'end_formatted' => number_format($model->end, 6, ',', '.'),
        ];
    }

    public function update()
    {
        $this->model->update($this->form);
        $this->emitUp('updated', $this->model);
    }

    public function delete()
    {
        $this->model->delete();
        $this->emitUp('deleted');
    }

    public function render()
    {
        return view('livewire.lifeareas.levels.goals.tr');
    }
}
