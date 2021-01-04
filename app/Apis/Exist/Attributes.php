<?php

namespace App\Apis\Exist;

use App\Apis\Model;
use App\Apis\Exist\Http;
use Carbon\Carbon;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;

class Attributes
{
    public static function index()
    {
        $response = Http::get('sync/last_activities');

        return $response->json();
    }
}