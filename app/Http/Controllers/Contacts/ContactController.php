<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Controllers\Controller;
use App\Models\Contacts\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ContactController extends Controller
{
    protected $baseViewPath = 'contact';

    public function __construct()
    {
        $this->authorizeResource(Contact::class, 'contact');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return auth()->user()->contacts()
                ->orderBy('first_name', 'ASC')
                ->orderBy('last_name', 'ASC')
                ->paginate();
        }

        return view($this->baseViewPath . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([

        ]);

        $contact = auth()->user()->contacts()->create([
            'first_name' => 'Neu',
        ]);

        if ($request->wantsJson()) {
            return $contact;
        }

        return redirect($contact->path, Response::HTTP_CREATED)
            ->with('status', [
                'type' => 'success',
                'text' => $contact->label(1) . ' gespeichert.',
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contacts\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $contact);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contacts\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $contact);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contacts\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        $attributes = $request->validate([
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'birthdate_at_formatted' => 'nullable|date_format:"d.m.Y"',
            'email' => 'nullable|email',
            'phone_number' => 'nullable|string',
            'mobile_number' => 'nullable|string',
            'website' => 'nullable|string',
            'twitter_id' => 'nullable|string',
            'instagram_id' => 'nullable|string',
            'first_met_at_formatted' => 'nullable|date_format:"d.m.Y"',
            'first_met_where' => 'nullable|string',
            'first_met_additional_info' => 'nullable|string',
            'job' => 'nullable|string',
            'street' => 'nullable|string',
            'city' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'first_parent_id' => 'nullable|exists:contacts,id',
            'second_parent_id' => 'nullable|exists:contacts,id',
        ]);

        $contact->update($attributes);

        if ($request->wantsJson()) {
            return $contact;
        }

        return redirect($contact->path)
            ->with('status', [
                'type' => 'success',
                'text' => $contact->label(1) . ' gespeichert.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contacts\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Contact $contact)
    {
        if ($isDeletable = $contact->isDeletable()) {
            $contact->delete();
        }

        if ($request->wantsJson()) {
            return [
                'deleted' => $isDeletable,
            ];
        }

        if ($isDeletable) {
            $status = [
                'type' => 'success',
                'text' => $contact->label(1) . ' gelöscht.',
            ];
        }
        else {
            $status = [
                'type' => 'danger',
                'text' => $contact->label(1) . ' konnte nicht gelöscht werden.',
            ];
        }

        return redirect($contact->index_path)
            ->with('status', $status);
    }
}
