@extends('layouts.app')

@section('content')

    <h1>Widgets {{ $view }}</h1>

    <form action="{{ \App\Models\Widgets\Users\User::indexPath(['view' => $view]) }}" method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col-auto d-flex align-items-start mb-1 mb-sm-0">
                <select class="form-control form-control-sm" name="widget">
                    <option value="" disabled selected>Widget wählen</option>
                    @foreach (\App\Models\Widgets\Users\User::WIDGETS as $tag => $name)
                        <option value="{{ $tag }}">{{ $name }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary btn-sm ml-1" type="submit">Speichern</button>
            </div>
        </div>
    </form>

    <table class="table table-fixed table-hover table-striped table-sm bg-white">
        <thead>
            <tr>
                <td>Widget</td>
                <td class="align-middle text-right" width="100">Aktion</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($widgets as $widget)
                <tr>
                    <td>{{ $widget->widget }}</td>
                    <td class="align-middle text-right">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ $widget->edit_path }}" class="btn btn-secondary" title="Bearbeiten"><i class="fas fa-fw fa-edit"></i></a>
                            <button type="submit" class="btn btn-secondary btn-sm" title="Löschen" onclick="event.preventDefault(); document.getElementById('widget_{{ $widget->id }}_destroy').submit();"><i class="fas fa-fw fa-trash"></i></button>
                        </div>
                        <form action="{{ $widget->path }}" class="ml-1" id="widget_{{ $widget->id }}_destroy" method="POST">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


@endsection