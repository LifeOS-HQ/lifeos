@extends('layouts.app')

@section('headline', $behaviour->name . ' > ' . \App\Models\Behaviours\History::label(1))

@section('content')

<behaviour-history-table :model="{{ json_encode($behaviour) }}" index-path="{{ \App\Models\Behaviours\History::indexPath(['behaviour_id' => $behaviour->id]) }}"></behaviour-history-table>

@endsection
