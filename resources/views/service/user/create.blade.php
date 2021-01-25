@extends('layouts.app')

@section('content')

<h1>Verbindungen > {{ $service->name }}</h1>

<div class="card">

</div>
<form action="{{ route('user.services.store', ['service' => $service->id]) }}" class="mt-3" method="POST">
    @csrf

    <div class="form-group">
        <label for="username" class="col-form-label text-md-right">Username</label>
        <div class="">
            <input id="username" type="username" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>

            @error('username')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="form-group">
        <label for="password" class="col-form-label text-md-right">{{ __('Password') }}</label>
        <div class="">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="form-group mb-0">
        <div class="">
            <button type="submit" class="btn btn-primary">
                Anlegen
            </button>
        </div>
    </div>

</form>

@endsection