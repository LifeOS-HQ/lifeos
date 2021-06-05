@extends('layouts.app')

@section('content')

<user-client-table index-path="{{ route('clients.index') }}"></user-client-table>

@endsection