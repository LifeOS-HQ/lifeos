@extends('layouts.app')

@section('headline', 'Ernährungstagebuch')

@section('buttons')
    <a href="{{ route('diet.index') }}" class="btn btn-secondary btn-sm ml-1">Übersicht</a>
@endsection

@section('content')

<diet-diary-table index-path="{{ \App\Models\Diet\Diary\Day::indexPath() }}"></diet-diary-table>

@endsection