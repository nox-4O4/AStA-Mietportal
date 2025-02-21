@extends('layouts.shop', ['title' => 'Seite nicht gefunden'])

{{-- Livewire does not inject assets into responses with status code != 200 (see SupportAutoInjectedAssets::provide()), so we have to inject assets on error page ourselves. --}}
@section('head')
    @livewireStyles
@endsection

@section('body_end')
    @livewireScripts
@endsection

@section('main')
    <div class="d-flex flex-column align-items-center text-center error-page">
        <div class="w-75 my-4 headtext">
            {!! File::get(resource_path('img/404.svg')) !!}
        </div>
        <p class="small text-muted m-0 text-italic">Klausurenphase?</p>
        <div class="w-100 subtext">
            {!! File::get(resource_path('img/not-found.svg')) !!}
        </div>
        <p class="mt-4 fs-5">Aber vielleicht findest du ja etwas anderes, das dir gefällt?</p>
        <p><a href="{{route('shop')}}" class="btn btn-success btn-lg" wire:navigate><i class="fa-solid fa-right-long"></i> Zu den Artikeln</a></p>
    </div>
@endsection
