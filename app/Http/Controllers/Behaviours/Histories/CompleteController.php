<?php

namespace App\Http\Controllers\Behaviours\Histories;

use Illuminate\Http\Response;
use App\Models\Behaviours\History;
use App\Http\Controllers\Controller;

class CompleteController extends Controller
{
    public function store(History $history)
    {
        if ($history->is_completed) {
            return response([
                'status' => [
                    'type' => 'danger',
                    'text' => 'Das Verhalten wurde bereits erledigt.',
                ]
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $history->update([
            'is_committed' => true,
            'is_completed' => true,
        ]);

        $history->load([
            'behaviour',
        ]);

        return $history;
    }

    public function destroy(History $history)
    {
        if (! $history->is_completed) {
            return response([
                'status' => [
                    'type' => 'danger',
                    'text' => 'Das Verhalten wurde noch nicht erledigt.',
                ]
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $history->update([
            'is_completed' => false,
        ]);

        $history->load([
            'behaviour',
        ]);

        return $history;
    }
}
