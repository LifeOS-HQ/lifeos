@extends('layouts.app')

@section('headline', $model->label())

@section('content')

<diet-diary-table index-path="{{ \App\Models\Diet\Diary\Day::indexPath() }}"></diet-diary-table>

@endsection