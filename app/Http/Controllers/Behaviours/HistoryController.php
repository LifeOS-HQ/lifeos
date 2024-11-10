<?php

namespace App\Http\Controllers\Behaviours;

use Illuminate\Http\Request;
use App\Models\Behaviours\History;
use App\Http\Controllers\Controller;
use App\Models\Behaviours\Behaviour;
use App\Models\Services\Data\Attributes\Groups\Group;

class HistoryController extends Controller
{
    protected $baseViewPath = 'behaviour.history';

    public function __construct()
    {
        $this->authorizeResource(History::class, 'history');
    }

    public function index(Request $request, Behaviour $behaviour)
    {
        if ($request->wantsJson()) {
            return $behaviour->histories()
                ->paginate();
        }

        return view($this->baseViewPath . '.index')
            ->with('behaviour', $behaviour);
    }

    public function store(Request $request, Behaviour $behaviour)
    {
        $attributes = $request->validate([
            'end_at_formatted' => 'required|date_format:d.m.Y H:i',
            'comment' => 'nullable|string',
        ]);

        $user = auth()->user();

        $history = $behaviour->histories()
            ->create($attributes + [
                'user_id' => $user->id,
            ]);

        if ($request->wantsJson()) {
            return $history;
        }

        return redirect($history->path)
            ->with('status', [
                'type' => 'success',
                'text' => $history->label(1) . ' gespeichert.',
            ]);
    }

    public function show(Behaviour $behaviour, History $history)
    {
        $history->load([
            'values.attribute',
        ]);

        $attribute_groups = Group::query()
            ->with([
                'attributes',
            ])
            ->withoutCustom()
            ->orderBy('name')
            ->get();

        return view($this->baseViewPath . '.show')
            ->with('attribute_groups', $attribute_groups)
            ->with('behaviour', $behaviour)
            ->with('model', $history);
    }

    public function edit(Behaviour $behaviour, History $history)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $history);
    }

    public function update(Request $request, Behaviour $behaviour, History $history)
    {
        $attributes = $request->validate([
            'start_at' => 'required|date_format:Y-m-d H:i:s',
            'end_at' => 'required|date_format:Y-m-d H:i:s',
            'comment' => 'nullable|string',
        ]);

        $history->update($attributes);

        if ($request->wantsJson()) {
            return $history;
        }

        return redirect($history->path)
            ->with('status', [
                'type' => 'success',
                'text' => $history->label(1) . ' gespeichert.',
            ]);
    }

    public function destroy(Request $request, Behaviour $behaviour, History $history)
    {
        if ($isDeletable = $history->isDeletable()) {
            $history->delete();
        }

        if ($request->wantsJson()) {
            return [
                'deleted' => $isDeletable,
            ];
        }

        if ($isDeletable) {
            $status = [
                'type' => 'success',
                'text' => $history->label(1) . ' gelöscht.',
            ];
        }
        else {
            $status = [
                'type' => 'danger',
                'text' => $history->label(1) . ' kann nicht gelöscht werden.',
            ];
        }

        return redirect($history->index_path)
            ->with('status', $status);
    }
}
