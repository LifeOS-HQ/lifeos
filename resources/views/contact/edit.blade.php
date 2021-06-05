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
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="first_name">Vorname</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('first_name') ? 'is-invalid' : '') }}" id="first_name" name="first_name" value="{{ old('first_name') ?? $model->first_name }}">
                                @if ($errors->has('first_name'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('first_name') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="last_name">Nachname</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('last_name') ? 'is-invalid' : '') }}" id="last_name" name="last_name" value="{{ old('last_name') ?? $model->last_name }}">
                                @if ($errors->has('last_name'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('last_name') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="birthdate_at_formatted">Geburtstag</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('birthdate_at_formatted') ? 'is-invalid' : '') }}" id="birthdate_at_formatted" name="birthdate_at_formatted" value="{{ old('birthdate_at_formatted') ?? $model->birthdate_at_formatted }}">
                                @if ($errors->has('birthdate_at_formatted'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('birthdate_at_formatted') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">Adresse</div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="street">Straße</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('street') ? 'is-invalid' : '') }}" id="street" name="street" value="{{ old('street') ?? $model->street }}">
                                @if ($errors->has('street'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('street') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="postal_code">PLZ</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('postal_code') ? 'is-invalid' : '') }}" id="postal_code" name="postal_code" value="{{ old('postal_code') ?? $model->postal_code }}">
                                @if ($errors->has('postal_code'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('postal_code') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="city">Stadt</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('city') ? 'is-invalid' : '') }}" id="city" name="city" value="{{ old('city') ?? $model->city }}">
                                @if ($errors->has('city'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('city') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="col-12 col-lg-6">

                <div class="card mb-3">
                    <div class="card-header">Kontakt</div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="phone_number">Telefon</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('phone_number') ? 'is-invalid' : '') }}" id="phone_number" name="phone_number" value="{{ old('phone_number') ?? $model->phone_number }}">
                                @if ($errors->has('phone_number'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('phone_number') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="mobile_number">Mobil</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('mobile_number') ? 'is-invalid' : '') }}" id="mobile_number" name="mobile_number" value="{{ old('mobile_number') ?? $model->mobile_number }}">
                                @if ($errors->has('mobile_number'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('mobile_number') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="email">E-Mail</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control form-control-sm {{ ($errors->has('email') ? 'is-invalid' : '') }}" id="email" name="email" value="{{ old('email') ?? $model->email }}">
                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('email') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="website">Webseite</label>
                            <div class="col-sm-8">
                                <input type="website" class="form-control form-control-sm {{ ($errors->has('website') ? 'is-invalid' : '') }}" id="website" name="website" value="{{ old('website') ?? $model->website }}">
                                @if ($errors->has('website'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('website') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">Social Media</div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="twitter_id">Twitter</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('twitter_id') ? 'is-invalid' : '') }}" id="twitter_id" name="twitter_id" value="{{ old('twitter_id') ?? $model->twitter_id }}">
                                @if ($errors->has('twitter_id'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('twitter_id') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="instagram_id">Instagram</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('instagram_id') ? 'is-invalid' : '') }}" id="instagram_id" name="instagram_id" value="{{ old('instagram_id') ?? $model->instagram_id }}">
                                @if ($errors->has('instagram_id'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('instagram_id') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">Kennengelernt</div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="first_met_at_formatted">Datum</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('first_met_at_formatted') ? 'is-invalid' : '') }}" id="first_met_at_formatted" name="first_met_at_formatted" value="{{ old('first_met_at_formatted') ?? $model->first_met_at_formatted }}">
                                @if ($errors->has('first_met_at_formatted'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('first_met_at_formatted') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="first_met_where">Ort</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('first_met_where') ? 'is-invalid' : '') }}" id="first_met_where" name="first_met_where" value="{{ old('first_met_where') ?? $model->first_met_where }}">
                                @if ($errors->has('first_met_where'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('first_met_where') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="first_met_additional_info">Info</label>
                            <div class="col-sm-8">
                                <textarea class="form-control form-control-sm {{ ($errors->has('first_met_additional_info') ? 'is-invalid' : '') }}" id="first_met_additional_info" name="first_met_additional_info">{{ old('first_met_additional_info') ?? $model->first_met_additional_info }}</textarea>
                                @if ($errors->has('first_met_additional_info'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('first_met_additional_info') }}
                                    </div>
                                @endif
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