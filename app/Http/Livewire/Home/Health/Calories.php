<?php

namespace App\Http\Livewire\Home\Health;

use App\Models\Services\Data\Attributes\Attribute;
use Livewire\Component;

class Calories extends Component
{
    public $energy;
    public $nutrients;

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
                'energy',
            ])->get();
        $this->energy = $attributes;

        $this->nutrients = Attribute::with([
                'values' => function ($query) {
                    return $query->where('user_id', auth()->user()->id)
                        ->latest('at')
                        ->take(30);
                },
            ])->whereIn('slug', [
                'carbohydrates',
                'fat',
                'protein',
            ])->get();

        foreach ($this->nutrients as $key => $nutrient) {
            $nutrient->values_avg = $nutrient->values->avg('raw');
            $nutrient->calories_avg = $nutrient->values_avg * ($nutrient->slug == 'fat' ? 9 : 4);
        }

    }

    public function render()
    {
        return view('livewire.home.health.calories');
    }
}
