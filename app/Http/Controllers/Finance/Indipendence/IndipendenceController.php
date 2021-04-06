<?php

namespace App\Http\Controllers\Finance\Indipendence;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndipendenceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {

        }

        return view('finance.indipendence.index');
    }
}
