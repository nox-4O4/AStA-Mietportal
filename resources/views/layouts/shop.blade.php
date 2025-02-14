@extends('layouts.base')

@section('content')
    <div class="d-flex flex-column min-h-100">
        <div class="sticky-top bg-light-subtle shadow">
            <div class="shop-content mx-auto p-3 p-lg-4">
                Topbar mit Logo, Suche und Warenkorb
            </div>
        </div>
        <div class="shop-content mx-auto w-100 p-3 pt-4 p-lg-4 pt-lg-5 flex-grow-1">
            {{$slot}}
        </div>
        <div class="mt-5 bg-body-tertiary shadow-sm border-top">
            <div class="shop-content mx-auto p-3 p-lg-4 text-body-secondary">
                <div class="row text-center mx-auto max-w-sm">
                    <div class="col-sm">
                        <a href="https://asta-hka.de/kontakt/" target="_blank">Kontakt</a>
                    </div>
                    <div class="col-sm">
                        <a href="https://asta-hka.de/datenschutzrichtlinien" target="_blank">Datenschutzerklärung</a>
                    </div>
                    <div class="col-sm">
                        <a href="https://asta-hka.de/impressum" target="_blank">Impressum</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
