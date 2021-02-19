<?php

namespace App\Http\Livewire\Workouts\Histories;

use App\Models\Workouts\Workout;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    /**
     * @var App\Models\Workouts\Workout
     */
    public $model;

    public function mount(Workout $model) : void
    {
        $this->model = $model;
    }

    protected function getItems() : LengthAwarePaginator
    {
        return $this->model->histories()->latest('start_at')->paginate();
    }

    public function render()
    {
        return view('livewire.workouts.histories.index')
            ->with('items', $this->getItems());
    }
}
