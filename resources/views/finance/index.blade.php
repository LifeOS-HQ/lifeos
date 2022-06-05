@extends('layouts.app')

@section('content')

    <h1>Finanzen</h1>
    <div class="mb-3">
        <a href="/portfolio" class="btn btn-secondary btn-sm">Portfolio</a>
        <a href="/finance/independence" class="btn btn-secondary btn-sm">Finanzielle Unabhängigkeit</a>
        <a href="#" class="btn btn-secondary btn-sm">Budget (TODO)</a>
        <a href="#" class="btn btn-secondary btn-sm">Konten (TODO)</a>
    </div>

    <div class="row">

        <div class="col">
            <home-rentablo-index></home-rentablo-index>
        </div>

    </div>

    <div class="row">

        <div class="col-12 col-lg-6">
            <finance-dividends-create></finance-dividends-create>
        </div>

        <div class="col-12 col-lg-6">
            <finance-investments-create></finance-investments-create>
        </div>

    </div>


@endsection