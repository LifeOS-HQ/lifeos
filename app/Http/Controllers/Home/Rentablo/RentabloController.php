<?php

namespace App\Http\Controllers\Home\Rentablo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class RentabloController extends Controller
{
    public function index(Request $request)
    {
        $rentabloApi = App::make('RentabloApi');
        if (is_null($rentabloApi)) {
            return [];
        }

        return $rentabloApi->home($request->input('refresh'));
    }
}