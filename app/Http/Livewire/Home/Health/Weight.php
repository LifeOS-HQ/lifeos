<?php

namespace App\Http\Livewire\Home\Health;

use App\Models\Services\Data\Attributes\Attribute;
use Livewire\Component;

class Weight extends Component
{
    public $items;
    public $current_weight_avg = 0;
    public $last_weight_avg = 0;
    public $body_fat_avg = 0;
    public $energy_avg = 0;
    public $weight_difference = 0;
    public $weight_difference_kcal = 0;
    public $weight_difference_goal = 0;
    public $weight_difference_goal_kcal = 0;

    public function loadItems()
    {
        $attributes = Attribute::with([
            ])->whereIn('slug', [
                'weight',
                'body_fat',
                'lean_mass',
            ])->get();

        $this->items = $attributes;

        foreach ($this->items as $key => $item) {
            $item->load([
                'values' => function ($query) {
                    return $query->where('user_id', auth()->user()->id)
                        ->latest('at')
                        ->take(30);
                },
            ]);
        }

        $body_fat_attribute = $attributes->where('slug', 'body_fat')->first();
        $this->body_fat_avg = $body_fat_attribute->values()->where('user_id', auth()->user()->id)->avg('raw');

        $weight_attribute = $attributes->where('slug', 'weight')->first();
        $current_weights = $weight_attribute->values()->where('user_id', auth()->user()->id)->latest('at')->limit(7)->offset(0)->get();
        $last_weights = $weight_attribute->values()->where('user_id', auth()->user()->id)->latest('at')->limit(7)->offset(1)->get();

        $this->current_weight_avg = $current_weights->avg('raw');
        $this->last_weight_avg = $last_weights->avg('raw');

        $this->weight_difference = $this->current_weight_avg - $this->last_weight_avg;
        $this->weight_difference_kcal = $this->weight_difference / 7 * 7000;

        $this->weight_difference_goal = $this->body_fat_avg / 20 / 2 * $this->current_weight_avg * -1;
        $this->weight_difference_goal_kcal = ($this->weight_difference_goal - $this->weight_difference) * 7000 / 7;

        $energy_attribute = Attribute::where('slug', 'energy')->first();
        $energies = $energy_attribute->values()->where('user_id', auth()->user()->id)->latest('at')->limit(7)->offset(0)->get();
        $this->energy_avg = $energy_attribute->value($energies->avg('raw'));
    }

    public function render()
    {
        return view('livewire.home.health.weight');
    }
}
