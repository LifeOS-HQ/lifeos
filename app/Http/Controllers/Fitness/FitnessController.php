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

        return view('fitness.index')
            ->with('widgets', \App\Models\Widgets\Users\User::where('user_id', auth()->user()->id)->where('view', 'fitness-index')->where('is_active', true)->orderBy('sort', 'ASC')->get());
    }
}
