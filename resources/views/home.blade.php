@extends('layouts.app')

@section('content')

    <h1>Home</h1>
    <div class="d-none d-md-block">
        <div class="text-center">
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
                        <div class="col-md-4 col-lg-3 col-lg-2 text-center mb-3">
                                <span class="mb-1">{{ $day->monthName }}</h5>
                                @if ($now->month > $day->month)
                                    <?php $days_over = $day->daysInMonth; ?>
                                    <?php $days_left = 0; ?>
                                @elseif ($now->month < $day->month)
                                    <?php $days_over = 0; ?>
                                    <?php $days_left = $day->daysInMonth; ?>
                                @elseif ($now->month == $day->month)
                                    <?php $days_over = $now->day - 1; ?>
                                    <?php $days_left = $day->daysInMonth - $days_over; ?>
                                @endif
                                {{ $days_over }} / {{ $day->daysInMonth }} @if ($now->month == $day->month) (noch {{ $days_left }} Tage) @endif
                            <div class="d-flex align-items-center justify-content-center">
                @endif
                <?php

                ?>
                <div data-toggle="popover" data-placement="bottom" data-trigger="hover" title="Bewertung {{ $moods[$day->format('Y-m-d')]['mood'] }}" data-content="{{ nl2br($moods[$day->format('Y-m-d')]['mood_note']) }}" style="width: 10px; height: 10px; border: 1px solid black;" class="@if($day < $now) {{ $moods[$day->format('Y-m-d')]['bg_class'] }} @else bg-light @endif"></div>
            @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-12 col-lg-6">

            <home-work-show class="mt-3"></home-work-show>

            <widget-health-weight></widget-health-weight>
            <widget-health-calories-show></widget-health-calories-show>
            <widget-health-macros-show></widget-health-macros-show>

            <!-- <home-server-index class="mt-3"></home-server-index> -->

        </div>

        <div class="col-12 col-lg-6">

            <home-rentablo-index class="mt-3"></home-rentablo-index>

        </div>

    </div>

@endsection
