<?php

namespace App\Http\Controllers\Wahoo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class WebhookController extends Controller
{
    public function store(Request $request)
    {
        $this->log($request);

        return [
            //
        ];
    }

    private function log(Request $request)
    {
        Storage::put('wahoo/webhook/' . now()->format('YmdHis') . '.json', json_encode([
            'headers' => $request->header(),
            'content' => $request->getContent(),
            'request' => $request->all(),
        ], JSON_PRETTY_PRINT));
    }
}
