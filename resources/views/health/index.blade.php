@extends('layouts.app')

@section('content')

    <h1>Gesundheit</h1>
    <div class="mb-3">

    </div>

    <div class="row">

        <div class="col-12 col-lg-6">

            @livewire('health.index.nutrition')

        </div>

        <div class="col-12 col-lg-6">



        </div>

    </div>

@endsection