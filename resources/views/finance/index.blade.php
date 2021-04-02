@extends('layouts.app')

@section('content')

    <h1>Finanzen</h1>
    <div>
        <a href="/portfolio" class="btn btn-secondary btn-sm">Portfolio</a>
        <a href="#" class="btn btn-secondary btn-sm">Budget (TODO)</a>
        <a href="#" class="btn btn-secondary btn-sm">Konten (TODO)</a>
    </div>

    <div class="row">

        <div class="col-12 col-lg-6">
            Ausgaben
        </div>

        <div class="col-12 col-lg-6">
            <home-rentablo-index></home-rentablo-index>
        </div>

    </div>


@endsection