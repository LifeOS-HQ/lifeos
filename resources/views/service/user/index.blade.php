@extends('layouts.app')

@section('content')

<h1>Verbindungen</h1>

<div class="mt-3">
    @foreach ($services as $service)
        @if ($service->type == 'oauth')
            <a href="{{ route('login.provider.redirect', ['provider' => $service->slug]) }}" type="button" class="btn btn-secondary ml-1">Verbinden mit {{ $service->name }}</a>
        @elseif ($service->type == 'password')
            <a href="{{ route('user.services.create', ['service' => $service->id]) }}" type="button" class="btn btn-secondary ml-1">Verbinden mit {{ $service->name }}</a>
        @endif
    @endforeach
</div>

@if ($user->services->count())
    <table class="mt-3 table table-striped table-hover table-fixed">
        <thead>
            <tr>
                <th>Verbindung</th>
                <th>User</th>
                <th>Verbunden seit</th>
                <th>Läuft aus</th>
                <th width="100"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($user->services as $service)
                <tr>
                    <td class="align-middle">{{ $service->name }}</td>
                    <td class="align-middle">
                        {{ $service->pivot->username }}
                        <div class="text-muted">{{ $service->pivot->service_user_id }}</div>
                    </td>
                    <td class="align-middle">{{ $service->pivot->updated_at->format('d.m.Y') }}</td>
                    <td class="align-middle">{{ $service->pivot->expires_at ? $service->pivot->expires_at->format('d.m.Y H:i:s') : 'Nie' }}</td>
                    <td class="align-middle text-right">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-secondary" title="Provider löschen" onclick="event.preventDefault(); document.getElementById('service_{{ $service->id }}_destroy').submit();"><i class="fas fa-trash"></i></button>
                        </div>
                        <form action="{{ route('user.services.destroy', ['service_user' => $service->pivot->id]) }}" method="POST" id="service_{{ $service->id }}_destroy">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

@endsection
