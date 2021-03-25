@extends('layouts.guest')

@section('content')
    <div class="jumbotron mb-0">
        <div class="container">
            <h1 class="display-4">Das Betriebssystem für dein Leben!</h1>
            <p class="lead">Tools für jeden Lebensbereich.</p>
            <hr class="my-4">
            <p>
                Ich versuche ständig mein Leben zu verbessern.
                LifeOS dient mir dabei als Werkzeug, um das umzusetzen.
                <a href="https://danielsundermeier.gitbook.io/knowledge/leben/leben" target="_blank">Hier</a> schreibe ich meine Gedanken auf.
                Die Notizen sind die Grundlage für diese Tools.
            </p>
            <a class="btn btn-primary btn-lg" href="{{ route('register')}}" role="button">Jetzt Beta-Tester werden</a>
            <div>
                <img class="img-fluid img-rounded mt-3" src="{{ Storage::disk('public')->url('landingpage/home.png') }}">
            </div>
        </div>
    </div>

    <section class="bg-dark text-white py-5">

        <div class="container">

            <div class="row">

                <div class="col-12 col-lg-6">

                    <h2>Arbeit</h2>
                    <h3>Arbeitszeit</h3>
                    <p>Tracke deine Arbeitszeit und werte die Daten aus. Wie viele Tage habe ich gearbeitet, wie viele Stunden arbeite ich pro Tag.</p>
                    <h3>Lohn</h3>
                    <p>Übersicht über deinen Brutto- und Nettolohn.</p>

                </div>
                <div class="col-12 col-lg-6">

                    <img class="img-fluid img-rounded" src="https://via.placeholder.com/686x440">

                </div>


            </div>

        </div>

    </section>

    <section class="py-5">

        <div class="container">

            <div class="row">

                <div class="col-12 col-lg-6">

                    <img class="img-fluid img-rounded" src="https://via.placeholder.com/686x440">

                </div>
                <div class="col-12 col-lg-6">

                    <h2>Berichte</h2>
                    <p>Reflektiere deine Woche.</p>
                    <p>Bist Du auf dem richtigen Weg? Was kannst Du besser machen?</p>

                </div>


            </div>

        </div>

    </section>

    <section class="bg-dark text-white py-5">

        <div class="container">

            <div class="row">

                <div class="col-12 col-lg-6">

                    <h2>Ernährung</h2>
                    <h3>Kalorien & Makros</h3>
                    <p>Tägliche Kalorienzufuhr und Makronährstoff Aufteilung</p>
                    <h3>Ernährungsplan</h3>
                    <p>In Planung.</p>

                </div>
                <div class="col-12 col-lg-6">

                    <img class="img-fluid img-rounded" src="https://via.placeholder.com/686x440">

                </div>


            </div>

        </div>

    </section>

    <section class="py-5">

        <div class="container">

            <div class="row">

                <div class="col-12 col-lg-6">

                    <img class="img-fluid img-rounded" src="https://via.placeholder.com/686x440">

                </div>
                <div class="col-12 col-lg-6">

                    <h2>Finanzen</h2>
                    <h3>Depot</h3>
                    <p>Verknüpfung deines Depot von <a href="https://rentablo.de/" target="_blank">Rentablo</a></p>
                    <h3>Ausgaben</h3>
                    <p>In Planung. Synchronisiere dein Konto und bekomme Überblick über deine Ausgaben.</p>
                    <h3>Budget</h3>
                    <p>In Planung</p>

                </div>


            </div>

        </div>

    </section>

    <section class="bg-dark text-white py-5">

        <div class="container">

            <div class="row">

                <div class="col-12 col-lg-6">

                    <h2>Fitness</h2>
                    <h3>Workouts</h3>
                    <p>Anzahl & Länge deiner Trainingseinheiten und Aktivitätskalorien.</p>
                    <h3>Trainingspläne und Tracker</h3>
                    <p>Plane deine Trainingseinheiten und verfolge deine Fortschritte.</p>

                </div>
                <div class="col-12 col-lg-6">

                    <img class="img-fluid img-rounded" src="https://via.placeholder.com/686x440">

                </div>


            </div>

        </div>

    </section>

    <section class="py-5">

        <div class="container">

            <div class="row">

                <div class="col-12 col-lg-6">

                    <img class="img-fluid img-rounded" src="https://via.placeholder.com/686x440">

                </div>
                <div class="col-12 col-lg-6">

                    <h2>Gesundheit</h2>
                    <h3>Daten vom Smartphone</h3>
                    <p>Synchronisiere deine Daten vom Smartphone oder anderen Trackern mit <a href="https://exist.to" target="_blank">Exists</a></p>
                    <h3>Schlaf</h3>
                    <p>Dein Schlaf ist die wichitgste Grundlage für einen erfolgreichen Tag. Verfolge deine Schlafenszeiten und erkenne Veränderungen.</p>
                    <h3>Schritte</h3>
                    <p>Bewegst Du duch genug?</p>

                </div>


            </div>

        </div>

    </section>

    <section class="bg-dark text-white py-5">

        <div class="container">

            <div class="row">

                <div class="col-12 col-lg-6">

                    <h2>Lebensbereiche</h2>
                    <p>Erstelle und bewerte deine Lebensbereiche</p>
                    <h3>Ziele</h3>
                    <p>Lege Ziele fest, um eine bessere Bewertung zu erhalten.</p>

                </div>
                <div class="col-12 col-lg-6">

                    <img class="img-fluid img-rounded" src="https://via.placeholder.com/686x440">

                </div>


            </div>

        </div>

    </section>

    <section class="py-5">

        <div class="container">

            <div class="row">

                <div class="col-12 col-lg-6">

                    <img class="img-fluid img-rounded" src="https://via.placeholder.com/686x440">

                </div>
                <div class="col-12 col-lg-6">

                    <h2>Tagebuch</h2>
                    <p>Finde heraus, was Du möchtest und was dich belastet indem Du deine Gedanken aufschreibst.</p>

                </div>


            </div>

        </div>

    </section>

@endsection