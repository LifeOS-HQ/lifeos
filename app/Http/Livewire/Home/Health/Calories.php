<?php

namespace App\Http\Livewire\Home\Health;

use App\Models\Services\Data\Attributes\Attribute;
use Livewire\Component;

class Calories extends Component
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
                'active_energy',
                'carbohydrates',
                'energy',
                'fat',
                'fibre',
                'protein',
            ])->get();

        $this->items = $attributes;
    }

    public function render()
    {
        return view('livewire.home.health.calories');
    }
}
