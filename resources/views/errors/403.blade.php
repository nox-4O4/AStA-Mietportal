@extends('layouts.base', ['title' => 'Fehlende Berechtigungen'])

@section('content')
    <div class="d-flex flex-column align-items-center text-center error-page">
        <div class="error-image mt-xl-4">
            <img src="{{ url('img/403.avif') }}" alt="You shall not pass!" class="w-100">
        </div>
        <p class="mt-4 fs-3">Dir fehlt leider die Berechtigung, diese Aktion auszuführen.</p>
        <p><a href="{{route('shop')}}" class="btn btn-success btn-lg" wire:navigate><i class="fa-solid fa-right-long"></i> Zurück zur Startseite</a></p>
    </div>
@endsection
