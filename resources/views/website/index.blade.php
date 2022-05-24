@extends('layouts.app')

@section('headline', \App\Models\Websites\Website::label())

@section('content')

    <div class="mb-3">
        <a href="{{ route('websites.errorlog.index') }}" class="btn btn-secondary btn-sm">Errorlog</a>
    </div>

    <website-table index-path="{{ \App\Models\Websites\Website::indexPath() }}"></website-table>

@endsection