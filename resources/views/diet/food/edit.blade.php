@extends('layouts.app')

@section('headline', $model->label() . ' > ' . $model->name)

@section('buttons')
    <a href="{{ $model->path }}" class="btn btn-secondary btn-sm ml-1">Übersicht</a>
@endsection

@section('content')

    <form action="{{ $model->path }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">

            <div class="col-12 col-lg-6">

                <div class="card mb-3">
                    <div class="card-header">Allgemein</div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="name">Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') ?? $model->name }}">
                                @error('name'))
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="calories_formatted">Kalorien / 100g</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('calories_formatted') is-invalid @enderror" id="calories_formatted" name="calories_formatted" value="{{ old('calories_formatted') ?? $model->calories_formatted }}">
                                @error('calories_formatted'))
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="fat_formatted">Fett / 100g</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('fat_formatted') is-invalid @enderror" id="fat_formatted" name="fat_formatted" value="{{ old('fat_formatted') ?? $model->fat_formatted }}">
                                @error('fat_formatted'))
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="carbohydrate_formatted">Kohlenhydrate / 100g</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('carbohydrate_formatted') is-invalid @enderror" id="carbohydrate_formatted" name="carbohydrate_formatted" value="{{ old('carbohydrate_formatted') ?? $model->carbohydrate_formatted }}">
                                @error('carbohydrate_formatted'))
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="protein_formatted">Protein_formatted / 100g</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('protein_formatted') is-invalid @enderror" id="protein_formatted" name="protein_formatted" value="{{ old('protein_formatted') ?? $model->protein_formatted }}">
                                @error('protein_formatted'))
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>

        <div class="row my-5"><div class="col"></div></div>

        <div class="fixed-bottom bg-white p-3 text-right">
            <button type="submit" class="btn btn-primary btn-sm">Speichern</button>
        </div>

    </form>

@endsection