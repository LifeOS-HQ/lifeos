@extends('layouts.app')

@section('content')

<div class="d-flex">

    <h2 class="col pl-0">{{ \App\Models\Days\Day::label() }}</h2>
    <div class="text-right">
        <a href="{{ \App\Models\Behaviours\Behaviour::indexPath() }}" class="btn btn-secondary btn-sm">{{ \App\Models\Behaviours\Behaviour::label() }}</a>
    </div>

</div>

<day-table index-path="{{ \App\Models\Days\Day::indexPath() }}"></day-table>

@endsection
