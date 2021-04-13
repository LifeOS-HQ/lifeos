@extends('layouts.app')

@section('content')

<h1>{{ \App\Models\Contacts\Contact::label() }}</h1>

<contact-table index-path="{{ \App\Models\Contacts\Contact::indexPath() }}"></contact-table>

@endsection