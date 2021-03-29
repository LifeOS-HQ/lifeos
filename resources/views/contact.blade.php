@extends('layouts.guest')

@section('content')

    <div class="container my-3">

        <div class="row">

            <div class="col-12">

                <div class="card">
                    <form action="contact" method="POST">
                        @csrf

                        <div class="card-header">Kontakt</div>
                        <div class="card-body">

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control {{ ($errors->has('name') ? 'is-invalid' : '') }}" id="name" name="name" placeholder="Name">
                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('name') }}
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="name">E-Mail</label>
                                <input type="email" class="form-control {{ ($errors->has('mail') ? 'is-invalid' : '') }}" id="mail" name="mail" placeholder="E-Mail">
                                @if ($errors->has('mail'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('mail') }}
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="name">Nachricht</label>
                                <textarea class="form-control {{ ($errors->has('message') ? 'is-invalid' : '') }}" id="message" name="message" rows="7"></textarea>
                                @if ($errors->has('message'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('message') }}
                                    </div>
                                @endif
                            </div>

                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Abschicken</button>
                        </div>
                    </form>

                </div>

            </div>

        </div>

    </div>

@endsection