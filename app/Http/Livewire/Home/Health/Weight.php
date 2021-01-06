<?php

namespace App\Http\Livewire\Home\Health;

use App\Models\Services\Data\Attributes\Attribute;
use Livewire\Component;

class Weight extends Component
{
    public $items;

    public function loadItems()
    {
        $attributes = Attribute::with([
                'values' => function ($query) {
                    return $query->where('user_id', auth()->user()->id)
                        ->latest('at')
                        ->take(30);
                },
            ])->whereIn('slug', [
                'weight',
                'body_fat',
                'lean_mass',
            ])->get();

        $this->items = $attributes;
    }

    public function render()
    {
        return view('livewire.home.health.weight');
    }
}
