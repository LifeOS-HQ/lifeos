<?php

namespace App\Http\Controllers\Days;

use App\Http\Controllers\Controller;
use App\Models\Days\Day;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DayController extends Controller
{
    protected $baseViewPath = 'day';

    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $user = auth()->user();

            return Day::query()
                ->where('user_id', $user->id)
                ->latest('date')
                ->paginate();
        }

        return view($this->baseViewPath . '.index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'date_formatted' => 'required|date_format:d.m.Y',
        ]);

        $user = auth()->user();

        $day = Day::firstOrCreate([
            'user_id' => $user->id,
            'date' => Carbon::createFromFormat('d.m.Y', $attributes['date_formatted'])->format('Y-m-d'),
        ], []);

        if ($request->wantsJson()) {
            return $day;
        }

        return redirect($day->path)
            ->with('status', [
                'type' => 'success',
                'text' => $day->label(1) . ' gespeichert.',
            ]);
    }

    public function show(Day $day)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $day);
    }

    public function edit(Day $day)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $day);
    }

    public function update(Request $request, Day $day)
    {
        $attributes = $request->validate([

        ]);

        $day->update($attributes);

        if ($request->wantsJson()) {
            return $day;
        }

        return redirect($day->path)
            ->with('status', [
                'type' => 'success',
                'text' => $day->label(1) . ' gespeichert.',
            ]);
    }

    public function destroy(Request $request, Day $day)
    {
        if ($isDeletable = $day->isDeletable()) {
            $day->delete();
        }

        if ($request->wantsJson()) {
            return [
                'deleted' => $isDeletable,
            ];
        }

        if ($isDeletable) {
            $status = [
                'type' => 'success',
                'text' => $day->label(1) . ' gelöscht.',
            ];
        }
        else {
            $status = [
                'type' => 'danger',
                'text' => $day->label(1) . ' kann nicht gelöscht werden.',
            ];
        }

        return redirect($day->index_path)
            ->with('status', $status);
    }
}
