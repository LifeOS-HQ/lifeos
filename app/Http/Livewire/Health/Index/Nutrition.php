<?php

namespace App\Http\Livewire\Health\Index;

use App\Models\Services\Data\Attributes\Attribute;
use Livewire\Component;

class Nutrition extends Component
{
    public $items = [];

    public function loadItems()
    {
        $attributes = Attribute::whereIn('slug', [
                'carbohydrates',
                'protein',
                'fat',
                'fibre',
                'sugar',
                'sodium',
                'cholesterol',
            ])->get();

        foreach ($attributes as $key => $attribute) {
            $attribute->values_avg_raw = $attribute->values()->latest('at')->take(7)->avg('raw');
        }

        $this->items = $attributes;
    }

    public function render()
    {
        return view('livewire.health.index.nutrition');
    }
}
