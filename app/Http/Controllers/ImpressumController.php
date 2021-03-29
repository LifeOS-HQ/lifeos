<?php

namespace App\Http\Controllers;

use App\Models\Services\Data\Attributes\Attribute;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class ImpressumController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('impressum');
    }
}
