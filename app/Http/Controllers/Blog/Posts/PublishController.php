<?php

namespace App\Http\Controllers\Blog\Posts;

use App\Http\Controllers\Controller;
use App\Models\Blog\Posts\Post;
use Illuminate\Http\Request;

class PublishController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Post $post)
    {
        $post->update([
            'published_at' => now(),
        ]);

        return redirect($post->path)
            ->with('status', [
                'type' => 'success',
                'text' => $post->label(1) . ' veröffentlicht.',
            ]);
    }
}
