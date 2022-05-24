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
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('name') ? 'is-invalid' : '') }}" id="name" name="name" value="{{ old('name') ?? $model->name }}">
                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('name') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="directory_path">Ordner Pfad</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('directory_path') ? 'is-invalid' : '') }}" id="directory_path" name="directory_path" value="{{ old('directory_path') ?? $model->directory_path }}">
                                @if ($errors->has('directory_path'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('directory_path') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="github_url">Github Url</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('github_url') ? 'is-invalid' : '') }}" id="github_url" name="github_url" value="{{ old('github_url') ?? $model->github_url }}">
                                @if ($errors->has('github_url'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('github_url') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="col-12 col-lg-6">



            </div>

        </div>

        <div class="row my-5"><div class="col"></div></div>

        <div class="fixed-bottom bg-white p-3 text-right">
            <button type="submit" class="btn btn-primary btn-sm">Speichern</button>
        </div>

    </form>

@endsection