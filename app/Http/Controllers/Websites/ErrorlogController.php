<?php

namespace App\Http\Controllers\Websites;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Websites\Website;
use App\Http\Controllers\Controller;

class ErrorlogController extends Controller
{
    protected $baseViewPath = 'website/errorlog';

    public function __construct()
    {
        $this->authorizeResource(Website::class, 'website');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $websites = auth()->user()->websites()
            ->orderBy('name', 'ASC')
            ->get();

        foreach ($websites as $website) {
            $logfiles = glob($website->directory_path . '/storage/logs/*.log');
            $contents = [];
            foreach ($logfiles as $logfile) {
                $contents[basename($logfile)] = nl2br(file_get_contents($logfile));
            }
            $website->logfiles = $contents;
        }

        if ($request->wantsJson()) {
            return $websites;
        }

        return view($this->baseViewPath . '.index')
            ->with('websites', $websites);
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
            'name' => 'required|string|max:255',
        ]);

        $model = auth()->user()->websites()->create($attributes);

        if ($request->wantsJson()) {
            return $model;
        }

        return redirect($model->path, Response::HTTP_CREATED)
            ->with('status', [
                'type' => 'success',
                'text' => $model->label(1) . ' gespeichert.',
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Websites\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function show(Website $website)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $website);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Websites\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function edit(Website $website)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $website);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Websites\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Website $website)
    {
        $attributes = $request->validate([
            'name' => 'required|string|max:255',
            'directory_path' => 'nullable|string|max:255',
            'github_url' => 'nullable|string|max:255',
        ]);

        $website->update($attributes);

        if ($request->wantsJson()) {
            return $website;
        }

        return redirect($website->path)
            ->with('status', [
                'type' => 'success',
                'text' => $website->label(1) . ' gespeichert.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Websites\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Website $website)
    {
        if ($isDeletable = $website->isDeletable()) {
            $website->delete();
        }

        if ($request->wantsJson()) {
            return [
                'deleted' => $isDeletable,
            ];
        }

        if ($isDeletable) {
            $status = [
                'type' => 'success',
                'text' => $website->label(1) . ' gelöscht.',
            ];
        }
        else {
            $status = [
                'type' => 'danger',
                'text' => $website->label(1) . ' kann nicht gelöscht werden.',
            ];
        }

        return redirect($website->index_path)
            ->with('status', $status);
    }
}
