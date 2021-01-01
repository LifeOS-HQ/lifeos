@extends('layouts.app')

@section('content')

    <h1>Home</h1>
    <div class="d-none d-md-block">
        <div>
            {{ $days_over }}/{{ $days_in_year }} (noch {{ $days_in_year - $days_over }} Tage)
        </div>
        <div class="row">
            @foreach ($days as $day)
                @if ($month != $day->month)
                    <?php $month = $day->month; ?>
                    @if (! $loop->first)
                            </div>
                        </div>
                    @endif
                        <div class="col-md-4 col-lg-3 col-lg-2">
                            <div class="text-center">
                                {{ $day->monthName }}
                            </div>
                            <div class="d-flex align-items-center justify-content-center">
                @endif
                <div style="width: 10px; height: 10px; border: 1px solid black;" class="@if($day < $now) bg-dark @else bg-light @endif"></div>
            @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-12 col-lg-6">

            <home-work-show class="mt-3"></home-work-show>
            <home-server-index class="mt-3"></home-server-index>

        </div>

        <div class="col-12 col-lg-6">

            <home-rentablo-index class="mt-3"></home-rentablo-index>

        </div>

    </div>

@endsection
