<?php

namespace App\Http\Controllers\Diet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DietController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {

        }

        return view('diet.index');
    }
}
