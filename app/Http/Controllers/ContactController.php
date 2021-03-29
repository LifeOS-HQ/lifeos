<?php

namespace App\Http\Controllers;

use App\Models\Services\Data\Attributes\Attribute;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'name' => 'required|string',
            'mail' => 'required|email',
            'message' => 'required|string',
        ]);

        Mail::to(config('mail.from.address'))
            ->queue(new \App\Mail\Contact($attributes));

        return back()->with('status', [
            'type' => 'success',
            'text' => 'Nachricht verschickt.',
        ]);
    }
}
