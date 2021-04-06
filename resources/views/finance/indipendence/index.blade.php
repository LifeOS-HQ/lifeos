@extends('layouts.app')

@section('content')

    <h1>Finanzielle Unabhängigkeit</h1>
    <div>
        <a href="{{ route('finance.index') }}" class="btn btn-secondary btn-sm">Finanzen</a>
    </div>

    <finance-indipendence-index class="mt-3"></finance-indipendence-index>

@endsection