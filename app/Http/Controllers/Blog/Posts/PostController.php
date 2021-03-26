<?php

namespace App\Http\Controllers\Blog\Posts;

use App\Http\Controllers\Controller;
use App\Models\Blog\Posts\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $baseViewPath = 'blog.post';

    public function __construct()
    {
        // $this->authorizeResource(Post::class, 'post');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Post::latest()->paginate();
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
            'title' => 'required|string',
        ]);

        $attributes['author_id'] = auth()->user()->id;

        $post = Post::create($attributes);

        return $post;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog\Posts\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view($this->baseViewPath . '.show')
            ->with('model', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog\Posts\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view($this->baseViewPath . '.edit')
            ->with('model', $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog\Posts\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $attributes = $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        $post->update($attributes);

        if ($request->wantsJson()) {
            return $post;
        }

        return redirect($post->path)
            ->with('status', [
                'type' => 'success',
                'text' => 'gespeichert.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog\Posts\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Post $post)
    {
        if ($isDeletable = $post->isDeletable()) {
            $post->delete();
        }

        if ($request->wantsJson()) {
            return [
                'deleted' => $isDeletable,
            ];
        }

        if ($isDeletable) {
            $status = [
                'type' => 'success',
                'text' => $post->label(1) . ' gelöscht.',
            ];
        }
        else {
            $status = [
                'type' => 'danger',
                'text' => $post->label(1) . ' kann nicht gelöscht werden.',
            ];
        }

        return redirect($post->index_path)
            ->with('status', $status);
    }
}
