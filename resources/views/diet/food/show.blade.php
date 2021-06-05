@extends('layouts.app')

@section('headline', $model->label() . ' > ' . $model->name)

@section('buttons')
    <a href="{{ $model->edit_path }}" class="btn btn-primary btn-sm ml-1"><i class="fas fa-edit"></i></a>
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

        <div class="col-lg-6">
            <div class="card mb-3">
                <div class="card-header">Allgemein</div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-label"><b>Name</b></div>
                        <div class="col-value">{{ $model->name }}</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Kalorien / 100g</b></div>
                        <div class="col-value">{{ $model->calories_formatted }} kcal</div>
                    </div>

                    <div class="row">
                        <div class="col-label"><b>Fett / 100g</b></div>
                        <div class="col-value">{{ $model->fat_formatted }} g</div>
                    </div>

                    <div class="row">
                        <div class="col-label"><b>Kohlenhydrate / 100g</b></div>
                        <div class="col-value">{{ $model->carbohydrate_formatted }} g</div>
                    </div>

                    <div class="row">
                        <div class="col-label"><b>Protein / 100g</b></div>
                        <div class="col-value">{{ $model->protein_formatted }} g</div>
                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-6">
            <div class="card mb-3">
                <div class="card-header">Verpackungen</div>
                <div class="card-body">

                    <diet-food-packaging-table index-path="{{ $model->packaging_path }}" :model="{{ json_encode($model) }}"></diet-food-packaging-table>

                </div>

            </div>

        </div>

    </div>

@endsection