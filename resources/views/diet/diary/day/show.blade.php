@extends('layouts.app')

@section('headline', $model->label() . ' > ' . $model->at_formatted)

@section('buttons')
    <a href="{{ $model->index_path }}" class="btn btn-secondary btn-sm ml-1">Übersicht</a>
    @if ($model->isDeletable())
        <form action="{{ $model->path }}" class="ml-1" method="POST">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-danger btn-sm" title="Löschen"><i class="fas fa-trash"></i></button>
        </form>
    @endif
@endsection

@section('content')

    <div class="row mb-3">

        <div class="col-12">

            <div class="card mb-3">
                <form action="{{ $model->path }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-header">Allgemein</div>
                    <div class="card-body">
                        - Mahlzeigen wie Blocks in Review erstellen<br>
                        - Wenn leer Mahlzeit hinzufügen<br>
                        - footer: neue Zeile mit select für Nahrungsmittel<br>

                        <div class="form-group">
                            <label class="col-form-label col-form-label-sm" for="rating_comment">Notiz</label>
                            <textarea class="form-control from-control-sm" name="rating_comment" id="rating_comment">{{ $model->rating_comment }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-primary btn-sm">Speichern</button>
                    </div>
                </form>
            </div>

        </div>

        <div class="col-12">
            <diet-diary-meal-index :model="{{ json_encode($model) }}" :foods="{{ json_encode($foods) }}" :diet_meals="{{ json_encode($diet_meals) }}"></diet-diary-meal-index>
        </div>

    </div>

@endsection
