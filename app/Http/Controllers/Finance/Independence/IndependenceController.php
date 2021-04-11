<?php

namespace App\Http\Controllers\Finance\Independence;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndependenceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {

        }

        return view('finance.independence.index');
    }
}
