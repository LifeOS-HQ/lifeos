@extends('layouts.app')

@section('content')

    <h1>Home</h1>

    <div class="row">

        <div class="col col-md-6">

            <home-work-show class="mt-3"></home-work-show>
            <home-server-index class="mt-3"></home-server-index>

        </div>

        <div class="col col-md-6">

            <home-rentablo-index class="mt-3"></home-rentablo-index>

        </div>

    </div>

@endsection
