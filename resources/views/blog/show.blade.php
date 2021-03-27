@extends('layouts.guest')

@section('content')

    <div class="container mt-3">

        <div class="row align-items-center mb-3">
            <div class="col">
                <h1 class="col mb-0 pl-0">{{ $model->title }}</h1>
            </div>
            <div id="buttons" class="col-auto d-flex align-items-center justify-content-around">
                <a class="btn btn-secondary btn-sm" href="{{ route('blog.index') }}">Übersicht</a>
            </div>
        </div>

        <div class="row">

            <div class="col-12 blog-post">

                {!! $model->body_markdown !!}

            </div>

        </div>

    </div>

@endsection