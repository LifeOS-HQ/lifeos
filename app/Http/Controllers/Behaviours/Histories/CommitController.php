<?php

namespace App\Http\Controllers\Behaviours\Histories;

use Illuminate\Http\Response;
use App\Models\Behaviours\History;
use App\Http\Controllers\Controller;

class CommitController extends Controller
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
        ]);

        return $history;
    }
}
