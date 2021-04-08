@extends('layouts.app')

@section('content')

    <h1>Tag {{ $day->format('d.m.Y') }}</h1>

    <div class="row">

        @foreach ($groups as $group)
            <div class="col-12 col-lg-6">

                <widget-day-show date-string="{{ $day->format('Y-m-d') }}" :group="{{ json_encode($group) }}"></widget-day-show>

            </div>
        @endforeach

    </div>

@endsection
