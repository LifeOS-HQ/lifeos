<?php

namespace App\Http\Controllers\Behaviours\Attributes;

use App\Http\Controllers\Controller;
use App\Models\Behaviours\Attributes\Attribute;
use App\Models\Behaviours\Behaviour;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    protected $baseViewPath = 'behaviour.attribute';

    public function index(Request $request, Behaviour $behaviour)
    {
        if ($request->wantsJson()) {
            return $behaviour->attributes()
                ->get();
        }

        return view($this->baseViewPath . '.index');
    }

    public function store(Request $request, Behaviour $behaviour)
    {
        $attributes = $request->validate([
            'attribute_id' => 'required|exists:data_attributes,id',
        ]);

        $user = auth()->user();

        $attribute = $behaviour->attributes()
            ->create($attributes + [
                'user_id' => $user->id,
            ]);

        if ($request->wantsJson()) {
            return $attribute->fresh()->load([
                'attribute',
            ]);
        }

        return redirect($attribute->path)
            ->with('status', [
                'type' => 'success',
                'text' => $attribute->label(1) . ' gespeichert.',
            ]);
    }

    public function show(Behaviour $behaviour, Attribute $attribute)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $attribute);
    }

    public function update(Request $request, Behaviour $behaviour, Attribute $attribute)
    {
        $attributes = $request->validate([
            'service_slug' => 'required|string',
            'default_number_formatted' => 'required|formatted_number',
            'goal_number_formatted' => 'required|formatted_number',
        ]);

        $attribute->update($attributes);

        if ($request->wantsJson()) {
            return $attribute->load([
                'attribute',
            ]);
        }

        return redirect($attribute->path)
            ->with('status', [
                'type' => 'success',
                'text' => $attribute->label(1) . ' gespeichert.',
            ]);
    }

    public function destroy(Request $request, Behaviour $behaviour, Attribute $attribute)
    {
        if ($isDeletable = $attribute->isDeletable()) {
            $attribute->delete();
        }

        if ($request->wantsJson()) {
            return [
                'deleted' => $isDeletable,
            ];
        }

        if ($isDeletable) {
            $status = [
                'type' => 'success',
                'text' => $attribute->label(1) . ' gelöscht.',
            ];
        }
        else {
            $status = [
                'type' => 'danger',
                'text' => $attribute->label(1) . ' kann nicht gelöscht werden.',
            ];
        }

        return redirect($attribute->index_path)
            ->with('status', $status);
    }
}
