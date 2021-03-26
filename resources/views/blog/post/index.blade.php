@extends('layouts.app')

@section('content')

<h1>Posts</h1>

<blog-post-table index-path="{{ \App\Models\Blog\Posts\Post::indexPath() }}"></blog-post-table>

@endsection