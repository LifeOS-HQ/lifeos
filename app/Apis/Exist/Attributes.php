<?php

namespace App\Apis\Exist;

use App\Apis\Exist\Http;

class Attributes
{
    public static function index()
    {
        $response = Http::get('sync/last_activities');

        return $response->json();
    }
}
