@extends('layouts.guest')

@section('content')

    <div class="container mt-3">
        <h1>Blog</h1>

        <table class="table table-sm table-fixed">
            <tbody>
                @foreach ($models as $model)
                    <tr>
                        <td width="100">{{ $model->published_at->format('d.m.Y') }}</td>
                        <td width="100%"><a href="{{ route('blog.show', ['post' => $model->slug]) }}">{{ $model->title }}</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection