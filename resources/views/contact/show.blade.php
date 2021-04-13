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
                        <div class="col-label"><b>Geburtstag</b></div>
                        <div class="col-value">{{ $model->birthdate_at_formatted }}</div>
                    </div>

                </div>

            </div>

            <div class="card mb-3">
                <div class="card-header">Adresse</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-label"><b>Adresse</b></div>
                        <div class="col-value">
                            {{ $model->name }}<br />
                            {{ $model->street }}<br />
                            {{ $model->postal_code }} {{ $model->city }}<br />
                            {{ $model->country }}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-lg-6">

            <div class="card mb-3">
                <div class="card-header">Kontakt</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-label"><b>Telefon</b></div>
                        <div class="col-value">{{ $model->phone_number }}</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Mobil</b></div>
                        <div class="col-value">{{ $model->mobile_number }}</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>E-Mail</b></div>
                        <div class="col-value">{{ $model->email }}</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Webseite</b></div>
                        <div class="col-value">{{ $model->website }}</div>
                    </div>
                    <div class="row">
                        <div class="col-label">&nbsp;</div>
                        <div class="col-value"></div>
                    </div>
                    @if ($model->twitter_id)
                        <div class="row">
                            <div class="col-label"><b>Twitter</b></div>
                            <div class="col-value">{{ $model->twitter_id }}</div>
                        </div>
                    @endif
                    @if ($model->instagram_id)
                        <div class="row">
                            <div class="col-label"><b>Instagram</b></div>
                            <div class="col-value">{{ $model->instagram_id }}</div>
                        </div>
                    @endif
                </div>

            </div>

            <div class="card mb-3">
                <div class="card-header">Kennengelern</div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-label"><b>Kennengelernt</b></div>
                        <div class="col-value">{{ $model->first_met_at_formatted }} @if ($model->first_met_where) ({{ $model->first_met_where }}) @endif</div>
                    </div>
                    @if ($model->first_met_where)
                        <div class="row">
                            <div class="col-label"><b>Ort</b></div>
                            <div class="col-value">{{ $model->first_met_where }}</div>
                        </div>
                    @endif
                    @if ($model->first_met_additional_info)
                        <div class="row mt-3">
                            <div class="col-md-12">{{ $model->first_met_additional_info }}</div>
                        </div>
                    @endif

                </div>

            </div>

        </div>

    </div>

@endsection