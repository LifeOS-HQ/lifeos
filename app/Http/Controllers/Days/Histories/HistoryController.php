<?php

namespace App\Http\Controllers\Days\Histories;

use App\Models\Days\Day;
use Illuminate\Http\Request;
use App\Models\Behaviours\History;
use App\Http\Controllers\Controller;

class HistoryController extends Controller
{
    public function store(Request $request, Day $day)
    {
        $attributes = $request->validate([
            'behaviour_id' => 'required|exists:behaviours,id',
            'ordinal' => 'required|integer',
        ]);

        $user = auth()->user();

        $history = $day->behaviourHistories()->create([
            'user_id' => $user->id,
            'behaviour_id' => $attributes['behaviour_id'],
            'start_at' => $day->date,
            'end_at' => $day->date,
            'ordinal' => $attributes['ordinal'],
            'is_committed' => true,
            'is_completed' => false,
        ]);

        $day->behaviourHistories()
            ->orderBy('ordinal')
            ->get()
            ->each(function ($history, $index) {
                $history->update([
                    'ordinal' => $index + 1,
                ]);
            });

        $history->load('behaviour');

        return $history;
    }

    public function update(Request $request, Day $day, History $history)
    {
        $attributes = $request->validate([
            'ordinal' => 'required|integer',
        ]);

        $history->update($attributes);

        $day->behaviourHistories()
            ->orderBy('ordinal')
            ->get()
            ->each(function ($history, $index) {
                $history->update([
                    'ordinal' => $index + 1,
                ]);
            });

        $history->load('behaviour');

        return $history;
    }

    public function destroy(Request $request, Day $day, History $history)
    {
        if ($isDeletable = $history->isDeletable()) {
            $history->delete();

            $day->behaviourHistories()
                ->orderBy('ordinal')
                ->get()
                ->each(function ($history, $index) {
                    $history->update([
                        'ordinal' => $index + 1,
                    ]);
                });
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
