<?php

namespace App\Http\Controllers\Widgets\Users;

use App\Http\Controllers\Controller;
use App\Models\Widgets\Users\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $baseViewPath = 'widget.user';

    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, string $view)
    {
        if ($request->wantsJson()) {
            //
        }

        return view($this->baseViewPath . '.index')
            ->with('view', $view)
            ->with('widgets', \App\Models\Widgets\Users\User::where('user_id', auth()->user()->id)->where('view', $view)->where('is_active', true)->orderBy('sort', 'ASC')->get());
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
    public function store(Request $request, string $view)
    {
        $attributes = $request->validate([
            'widget' => 'required|string',
        ]);

        $attributes['user_id'] = auth()->user()->id;
        $attributes['view'] = $view;

        $user = User::create($attributes);

        if ($request->wantsJson()) {
            return $user;
        }

        return back()
            ->with('status', [
                'type' => 'success',
                'text' => $user->label(1) . ' gespeichert.',
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Widgets\Users\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(string $view, User $user)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Widgets\Users\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(string $view, User $user)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Widgets\Users\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $view, User $user)
    {
        $attributes = $request->validate([

        ]);

        $user->update($attributes);

        if ($request->wantsJson()) {
            return $user;
        }

        return back()
            ->with('status', [
                'type' => 'success',
                'text' => 'gespeichert.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Widgets\Users\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, string $view, User $user)
    {
        if ($isDeletable = $user->isDeletable()) {
            $user->delete();
        }

        if ($request->wantsJson()) {
            return [
                'deleted' => $isDeletable,
            ];
        }

        if ($isDeletable) {
            $status = [
                'type' => 'success',
                'text' => $user->label(1) . ' gelöscht.',
            ];
        }
        else {
            $status = [
                'type' => 'danger',
                'text' => $user->label(1) . ' konnte nicht gelöscht werden.',
            ];
        }

        return redirect($user->index_path)
            ->with('status', $status);
    }
}
