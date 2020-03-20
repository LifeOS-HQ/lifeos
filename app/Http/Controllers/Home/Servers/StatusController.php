<?php

namespace App\Http\Controllers\Home\Servers;

use App\Http\Controllers\Controller;
use App\Support\Servers\Uptime;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class StatusController extends Controller
{
    public function index(Request $request)
    {
        $uptime = Uptime::get();

        return [
            'uptime' => $uptime->toArray(),
        ];
    }
}
