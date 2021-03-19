<?php

namespace App\Http\Controllers\Fitness;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FitnessController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {

        }

        return view('fitness.index');
    }
}
