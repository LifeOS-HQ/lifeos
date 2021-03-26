<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog\Posts\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    protected $baseViewPath = 'blog';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Post::whereNotNull('published_at')->latest('published_at')->get();
        }

        $models = Post::whereNotNull('published_at')->latest('published_at')->get();
        return view($this->baseViewPath . '.index')
            ->with('models', $models);
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
}
