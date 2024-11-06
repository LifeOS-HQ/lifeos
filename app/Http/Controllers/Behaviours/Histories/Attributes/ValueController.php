<?php

namespace App\Http\Controllers\Behaviours\Histories\Attributes;

use Illuminate\Http\Request;
use App\Models\Behaviours\History;
use App\Http\Controllers\Controller;
use App\Models\Behaviours\Histories\Attributes\Value;

class ValueController extends Controller
{
    protected $baseViewPath = 'history.history.value';

    public function index(Request $request, History $history)
    {
        if ($request->wantsJson()) {
            return $history->values()
                ->paginate();
        }

        return view($this->baseViewPath . '.index')
            ->with('history', $history);
    }

    public function store(Request $request, History $history)
    {
        $attributes = $request->validate([
            'attribute_id' => 'required|exists:data_attributes,id',
            'number_formatted' => 'required|formatted_number',
        ]);

        $user = auth()->user();

        $value = $history->values()
            ->create($attributes + [
                'user_id' => $user->id,
            ]);

        if ($request->wantsJson()) {
            return $value;
        }

        return redirect($value->path)
            ->with('status', [
                'type' => 'success',
                'text' => $value->label(1) . ' gespeichert.',
            ]);
    }

    public function show(History $history, Value $value)
    {
        return view($this->baseViewPath . '.show')
            ->with('history', $history)
            ->with('model', $value);
    }

    public function edit(History $history, Value $value)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $value);
    }

    public function update(Request $request, History $history, Value $value)
    {
        $attributes = $request->validate([
            'number_formatted' => 'required|formatted_number',
        ]);

        $value->update($attributes);

        if ($request->wantsJson()) {
            return $value;
        }

        return redirect($value->path)
            ->with('status', [
                'type' => 'success',
                'text' => $value->label(1) . ' gespeichert.',
            ]);
    }

    public function destroy(Request $request, History $history, Value $value)
    {
        if ($isDeletable = $value->isDeletable()) {
            $value->delete();
        }

        if ($request->wantsJson()) {
            return [
                'deleted' => $isDeletable,
            ];
        }

        if ($isDeletable) {
            $status = [
                'type' => 'success',
                'text' => $value->label(1) . ' gelöscht.',
            ];
        }
        else {
            $status = [
                'type' => 'danger',
                'text' => $value->label(1) . ' kann nicht gelöscht werden.',
            ];
        }

        return redirect($value->index_path)
            ->with('status', $status);
    }
}
